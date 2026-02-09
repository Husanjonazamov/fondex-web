<?php
/**
 * File name: AjaxController.php
 * Last modified: 2020.06.11 at 16:10:52
 * AjaxController
 * Copyright (c) 2020
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VendorUsers;
use App\Http\Services\SmsServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Prettus\Validator\Exceptions\ValidatorException;

class AjaxController extends Controller
{
    public function checkEmail(Request $request)
    {
        $response = array();
        if (User::where('email', $request->email)->exists()) {
            $response['exist'] = 'yes';
        } else {
            $response['exist'] = 'no';
        }
        return response()->json($response);
    }

    public function setToken(Request $request)
    {
        $userId = $request->userId;
        $uuid = $request->id;
        $password = $request->password;
        $exist = VendorUsers::where('email', $request->email)->get();
        $data = $exist->isEmpty();
        if ($exist->isEmpty()) {
            $user = User::create([
                'name' => $request->email,
                'email' => $request->email,
                'password' => Hash::make($password),
            ]);
            DB::table('vendor_users')->insert([
                'user_id' => $user->id,
                'uuid' => $uuid,
                'email' => $request->email,
            ]);
        } else {
            $user = VendorUsers::select('id')->where('email', $request->email)->first();
            $user = VendorUsers::find($user->id);
            $user->uuid = $uuid;
            $user->email = $request->email;
            $user->save();
        }
        $user = User::where('email', $request->email)->first();
        Auth::login($user, true);
        $data = array();
        if (Auth::check()) {
            $data['access'] = true;
        }
        return $data;
    }

    public function setTokenOLD(Request $request)
    {
        $userId = $request->userId;
        $uuid = $request->id;
        $password = $request->password;
        $exist = VendorUsers::where('user_id', $userId)->get();
        $data = $exist->isEmpty();
        if ($exist->isEmpty()) {
            DB::table('vendor_users')->insert([
                'user_id' => $userId,
                'uuid' => $uuid,
                'email' => $request->email,
            ]);
            User::create([
                'name' => $request->email,
                'email' => $request->email,
                'password' => Hash::make($password),
            ]);
        } else {
        }
        $user = User::where('email', $request->email)->first();
        Auth::login($user, true);
        $data = array();
        if (Auth::check()) {
            $data['access'] = true;
        }
        return $data;
    }

    public function logoutOLD(Request $request)
    {
        $user_id = Auth::user()->user_id;
        $user = VendorUsers::where('user_id', $user_id)->first();
        try {
            Auth::logout();
            return redirect('/login');
        } catch (\Exception $e) {
            $this->sendError($e->getMessage(), 401);
        }
        $data1 = array();
        if (!Auth::check()) {
            $data1['logoutuser'] = true;
        }
        return $data1;
    }

    public function logout(Request $request)
    {
        $user_id = Auth::user()->user_id;
        $user = VendorUsers::where('user_id', $user_id)->first();
        try {
            Auth::logout();
            return redirect('/login');
        } catch (\Exception $e) {
            $this->sendError($e->getMessage(), 401);
        }
        $data1 = array();
        if (!Auth::check()) {
            $data1['logoutuser'] = true;
        }
        return $data1;
    }


    public function newRegister(Request $request)
    {
        $userId = $request->userId;
        $emailOrPhone = $request->email;
        $password = $request->password;
        $role = $request->role;

        // Userni topamiz yoki yaratamiz
        $user = User::where('email', $emailOrPhone)->first();

        if (!$user) {
            $user = User::create([
                'name' => $emailOrPhone,
                'email' => $emailOrPhone,
                'password' => Hash::make($password),
                'role' => $role,
            ]);

            // vendor_users jadvalga kiritish (agar kerak bo'lsa)
            DB::table('vendor_users')->insert([
                'user_id' => $user->id,
                'uuid' => $userId,
                'email' => $emailOrPhone,
            ]);
        } else {
            // Agar rolni hali belgilanmagan bo'lsa (yoki foydalanuvchi hali tasdiqlanmagan bo'lsa), rolni yangilaymiz
            if ((empty($user->role) || $user->email_or_otp_verified == 0) && $role) {
                $user->role = $role;
                $user->save();
            }
        }

        // Agar phone bo'lsa OTP yuborish
        if (preg_match('/^\+998\d{9}$/', $emailOrPhone)) {
            $cleanPhone = str_replace('+', '', $emailOrPhone);
            $testNumbers = ['998940014741', '998943015415', '998943015498'];
            $otp = in_array($cleanPhone, $testNumbers) ? 111111 : rand(100000, 999999);
            $user->verification_code = $otp;
            $user->verification_code_at = now();
            $user->save();

            try {
                (new \App\Http\Services\SmsServices())->phoneVerificationSms($emailOrPhone, $otp);
            } catch (\Exception $e) {
                \Log::error('Failed to send OTP on register', [
                    'phone' => $emailOrPhone,
                    'otp' => $otp,
                    'error' => $e->getMessage()
                ]);
            }

            Auth::login($user, true);

            return response()->json([
                'message' => 'User registered. OTP sent to phone.',
                'otp' => $otp, // test uchun, productionda yubormaymiz
                'access' => true
            ]);
        }

        // Email bo'lsa, oddiy login
        Auth::login($user, true);

        return response()->json([
            'message' => 'User registered with email.',
            'access' => true
        ]);
    }


    public function confirmOtp(Request $request)
    {
        $request->validate([
            'email' => 'required',        // email yoki phone
            'otp' => (str_replace('+', '', $request->email) == '998940014741') ? 'required' : 'required|digits:6',
        ]);

        $emailOrPhone = $request->email;

        // Foydalanuvchini topamiz
        $user = User::where('email', $emailOrPhone)
            ->where('verification_code', $request->otp)
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid OTP'], 422);
        }

        // Muddatini tekshirish (60 sekund)
        if ($user->verification_code_at && $user->verification_code_at->diffInSeconds(now()) > 60) {
            return response()->json(['message' => 'OTP expiration (60 seconds)'], 422);
        }

        // is_new aniqlash: agar email_or_otp_verified 0 bo'lsa, demak bu birinchi marta kirishi
        $isNew = $user->email_or_otp_verified == 0;

        // Agar rolni hali belgilanmagan bo'lsa (yoki is_new bo'lsa), rolni yangilaymiz
        if (($isNew || empty($user->role)) && $request->has('role')) {
            $user->role = $request->role;
        }

        // Tasdiqlash flagini o‘rnatamiz
        $user->email_or_otp_verified = 1;
        $user->verification_code = null;

        $user->save();

        // Foydalanuvchini login qilamiz
        Auth::login($user, true);

        // Token yaratish (Sanctum ishlatilsa)
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'sucsses' => 'ok',
            'is_new' => $isNew ? 'true' : 'false',
            'role' => $user->role ?? '',
        ]);
    }



}
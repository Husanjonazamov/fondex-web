<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Services\SmsServices;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

class VerificationController extends Controller
{
    protected $firestore;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(config('firebase.credentials_file'));
        $this->firestore = $factory->createFirestore()->database();
    }

    /**
     * Show phone verification form.
     */
    public function show(Request $request)
    {
        return redirect()->route('verification.phone');
    }

    /**
     * Show phone verification page and send OTP if not already sent.
     */
    public function verifyPhone(Request $request)
    {
        $userPhone = auth()->user()->phone;

        $userDocs = $this->firestore->collection('users')
            ->where('phoneNumber', '=', $userPhone)
            ->documents();

        if ($userDocs->isEmpty()) {
            abort(404, 'User not found in Firestore');
        }

        $userDoc = $userDocs[0];

        // Generate OTP
        $otp = rand(100000, 999999);

        // Save OTP in Firestore
        $this->firestore->collection('users')->document($userDoc->id())
            ->update([
                ['path' => 'verification_code', 'value' => $otp],
            ]);

        // Send OTP via SMS
        $this->sendOtp($userPhone, $otp);

        return view('auth.phoneVerify', ['user' => $userDoc]);
    }

    public function sendOtp($phone, $otp)
    {
        (new SmsServices)->phoneVerificationSms($phone, $otp);
        flash('A verification code has been sent to your phone.')->info();
    }

    /**
     * Confirm OTP from user input
     */
    public function phone_verification_confirmation(Request $request)
    {
        $otpInput = $request->verification_code;
        $userPhone = auth()->user()->phone;

        $userDocs = $this->firestore->collection('users')
            ->where('phoneNumber', '=', $userPhone)
            ->documents();

        if ($userDocs->isEmpty()) {
            abort(404, 'User not found in Firestore');
        }

        $userDoc = $userDocs[0];
        $userData = $userDoc->data();

        if (!isset($userData['verification_code'])) {
            flash('No OTP found. Please request a new one.')->error();
            return redirect()->back();
        }

        if ($userData['verification_code'] == $otpInput) {
            // Mark as verified
            $this->firestore->collection('users')->document($userDoc->id())
                ->update([
                    ['path' => 'email_or_otp_verified', 'value' => 1],
                ]);

            flash('Your account has been verified successfully.')->success();
            return redirect()->route('customers.dashboard');
        } else {
            flash('OTP is incorrect. Please try again.')->error();
            return redirect()->back();
        }
    }
}

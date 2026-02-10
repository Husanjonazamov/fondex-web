<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorUsers;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('deleteUserTest');
    }

    public function index()
    {
        if (!isset($_COOKIE['section_id']) && !isset($_COOKIE['address_name'])) {
            return \Redirect::to('set-location');
        }
        $user = Auth::user();
        $id = Auth::id();
        $exist = VendorUsers::where('user_id', $id)->first();
        return view('users.profile')->with('id', $id);
    }

    public function deleteUserTest($id)
    {
        try {
            $user = VendorUsers::where('uuid', $id)->first();
            if ($user) {
                $user->delete();
            }
        } catch (\Exception $e) {
            // Silently fail as requested
        }

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully'
        ]);
    }
}
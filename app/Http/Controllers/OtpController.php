<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhoneVerification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class OtpController extends Controller
{
    public function show()
    {
        abort_unless(session()->has('pending_user_id'), 403);
        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate(['otp_code' => 'required|digits:6']);
        $user = User::findOrFail(session('pending_user_id'));

        $verification = PhoneVerification::where('user_id', $user->id)
                        ->whereNull('verified_at')->latest()->first();

        if (!$verification ||
            now()->gt($verification->expires_at) ||
            !Hash::check($request->otp_code, $verification->otp_hash)) {
            return back()->withErrors(['otp_code' => 'Invalid or expired OTP']);
        }

        $verification->update(['verified_at' => now()]);
        $user->update(['otp_verified' => true]);

        session()->forget('pending_user_id');
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Phone verified!');
    }
}

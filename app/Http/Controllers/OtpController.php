<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Twilio\Rest\Client;

class OtpController extends Controller
{
    public function show()
    {
        return view('auth.otp-verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $data = session('otp_registration_data');

        if (!$data) {
            return redirect()->route('register')->withErrors(['session' => 'Session expired. Please register again.']);
        }

        $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));

        $verification = $twilio->verify->v2->services(env('TWILIO_VERIFY_SID'))
            ->verificationChecks
            ->create([
                'to'   => $data['mobile_number'],
                'code' => $request->code,
            ]);

        if ($verification->status === 'approved') {
            $user = User::create([
                'name'          => $data['name'],
                'mobile_number' => $data['mobile_number'],
                'password'      => Hash::make($data['password']),
                'role'          => $data['role'],
                'otp_verified'  => true,
            ]);

            session()->forget('otp_registration_data');

            Auth::login($user);

            return redirect()->route('dashboard')->with('success', 'Phone verified. Welcome!');
        }

        return back()->withErrors(['code' => 'The verification code is invalid.']);
    }
}

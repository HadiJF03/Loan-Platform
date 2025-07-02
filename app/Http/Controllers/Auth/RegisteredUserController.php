<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PhoneVerification;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Twilio\Rest\Client;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'mobile_number' => 'required|string|unique:users',
            'role' => 'required|in:pledger,pledgee',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $data['mobile_number'] = '+966' . ltrim($data['mobile_number'], '0');
        session(['otp_registration_data' => $data]);

        try {
            $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));

            $twilio->verify->v2->services(env('TWILIO_VERIFY_SID'))
                ->verifications->create($data['mobile_number'], 'sms');
        } catch (\Exception $e) {
            dd('Twilio Error:', $e->getMessage());
            return back()->withErrors(['twilio' => 'OTP send failed: ' . $e->getMessage()]);
        }

        return redirect()->route('otp.form')->with('info', 'We\'ve sent a verification code to your phone.');
    }


}

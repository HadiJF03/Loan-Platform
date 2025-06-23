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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'mobile_number' => 'required|string|unique:users',
            'role' => 'required|in:pledger,pledgee',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $Otp = rand(100000, 999999);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'role'          => $request->role,
            'otp_verified'  => false,
            'password' => Hash::make($request->password),
        ]);

        PhoneVerification::updateOrCreate(
        ['user_id' => $user->id, 'verified_at' => null],
        [
            'otp_hash'    => Hash::make($Otp),
            'expires_at'  => now()->addMinutes(5),
        ]
        );

        (new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN')))->messages->create($user->mobile_number, [
            'from' => env('TWILIO_PHONE'),
            'body' => "Your verification code is: $Otp"
        ]);

        session(['pending_user_id' => $user->id]);

        return redirect()->route('otp.form')->with('info', 'We\'ve sent a verification code to your phone.');
    }
}

<x-guest-layout>
    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf
        <div>
            <x-input-label for="otp_code" :value="'OTP Code'" />
            <x-text-input id="otp_code" name="otp_code" type="text" class="block mt-1 w-full" required autofocus />
            <x-input-error :messages="$errors->get('otp_code')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-primary-button>{{ __('Verify') }}</x-primary-button>
        </div>
    </form>
</x-guest-layout>

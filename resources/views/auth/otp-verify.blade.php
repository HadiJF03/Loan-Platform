<x-guest-layout>
    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf
        <div>
            <x-input-label for="code" :value="'OTP Code'" />
            <x-text-input id="code" name="code" type="text" class="block mt-1 w-full" required autofocus />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-primary-button>{{ __('Verify') }}</x-primary-button>
        </div>
    </form>
</x-guest-layout>

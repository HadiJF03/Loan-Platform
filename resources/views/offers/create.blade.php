<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Make an Offer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Pledge Summary -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded p-4 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">Pledge Details</h3>
                <p><strong>Description:</strong> {{ $pledge->description }}</p>
                <p><strong>Category:</strong> {{ $pledge->category->name ?? 'N/A' }}</p>
                <p><strong>Requested Amount:</strong> {{ number_format($pledge->requested_amount, 2) }} SAR</p>
                <p><strong>Collateral Duration:</strong> {{ $pledge->collateral_duration }} days</p>
                <p><strong>Repayment Terms:</strong> {{ $pledge->repayment_terms }}</p>
            </div>

            <!-- Offer Form -->
            <form method="POST" action="{{ route('offers.store', $pledge->id) }}">
                @csrf

                <!-- Offer Amount -->
                <div class="mb-4">
                    <x-input-label for="offer_amount" :value="__('Offer Amount')" />
                    <x-text-input id="offer_amount" name="offer_amount" type="number" step="0.01"
                        class="block mt-1 w-full" value="{{ old('offer_amount') }}" required />
                    <x-input-error :messages="$errors->get('offer_amount')" class="mt-2" />
                </div>

                <!-- Duration -->
                <div class="mb-4">
                    <x-input-label for="duration" :value="__('Duration (days)')" />
                    <x-text-input id="duration" name="duration" type="number"
                        class="block mt-1 w-full" value="{{ old('duration') }}" required />
                    <x-input-error :messages="$errors->get('duration')" class="mt-2" />
                </div>

                <!-- Terms -->
                <div class="mb-4">
                    <x-input-label for="terms" :value="__('Terms (optional)')" />
                    <textarea id="terms" name="terms"
                        class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('terms') }}</textarea>
                    <x-input-error :messages="$errors->get('terms')" class="mt-2" />
                </div>

                <!-- Submit -->
                <div class="flex justify-end">
                    <x-primary-button>
                        {{ __('Submit Offer') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

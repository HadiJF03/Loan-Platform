<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Amend Offer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded p-6">
                <form action="{{ route('offers.amend', $offer->id) }}" method="POST">
                    @csrf

                    <!-- Offer Amount -->
                    <div class="mb-4">
                        <x-input-label for="offer_amount" :value="__('Offer Amount')" />
                        <x-text-input
                            id="offer_amount"
                            name="offer_amount"
                            type="number"
                            step="0.01"
                            class="block mt-1 w-full"
                            value="{{ old('offer_amount', $offer->offer_amount) }}"
                            required
                        />
                        <x-input-error :messages="$errors->get('offer_amount')" class="mt-2" />
                    </div>

                    <!-- Duration -->
                    <div class="mb-4">
                        <x-input-label for="duration" :value="__('Duration (days)')" />
                        <x-text-input
                            id="duration"
                            name="duration"
                            type="number"
                            class="block mt-1 w-full"
                            value="{{ old('duration', $offer->duration) }}"
                            required
                        />
                        <x-input-error :messages="$errors->get('duration')" class="mt-2" />
                    </div>

                    <!-- Terms -->
                    <div class="mb-4">
                        <x-input-label for="terms" :value="__('Terms (optional)')" />
                        <textarea
                            id="terms"
                            name="terms"
                            class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md shadow-sm"
                            rows="4"
                        >{{ old('terms', $offer->terms) }}</textarea>
                    </div>

                    <!-- Submit -->
                    <div class="flex justify-end">
                        <x-primary-button>
                            {{ __('Submit Amended Offer') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

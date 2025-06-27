<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Pledge') }}
        </h2>
    </x-slot>

    @can('update', $pledge)
    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 dark:bg-gray-800">
                <form method="POST" action="{{ route('pledges.update', $pledge->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Description -->
                    <div class="mb-4">
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" rows="4" required
                            class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('description', $pledge->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <!-- Requested Amount -->
                    <div class="mb-4">
                        <x-input-label for="requested_amount" :value="__('Requested Amount')" />
                        <x-text-input id="requested_amount" name="requested_amount" type="number" step="0.01" required
                            class="block mt-1 w-full" value="{{ old('requested_amount', $pledge->requested_amount) }}" />
                        <x-input-error :messages="$errors->get('requested_amount')" class="mt-2" />
                    </div>

                    <!-- Collateral Duration -->
                    <div class="mb-4">
                        <x-input-label for="collateral_duration" :value="__('Collateral Duration (Days)')" />
                        <x-text-input id="collateral_duration" name="collateral_duration" type="number" required
                            class="block mt-1 w-full" value="{{ old('collateral_duration', $pledge->collateral_duration) }}" />
                        <x-input-error :messages="$errors->get('collateral_duration')" class="mt-2" />
                    </div>

                    <!-- Repayment Terms -->
                    <div class="mb-4">
                        <x-input-label for="repayment_terms" :value="__('Repayment Terms')" />
                        <textarea id="repayment_terms" name="repayment_terms" rows="3"
                            class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('repayment_terms', $pledge->repayment_terms) }}</textarea>
                        <x-input-error :messages="$errors->get('repayment_terms')" class="mt-2" />
                    </div>

                    <!-- Image Upload -->
                    <div class="mb-4">
                        <x-input-label for="images" :value="__('Upload New Images (optional, will replace existing)')" />
                        <input id="images" name="images[]" type="file" multiple accept="image/*"
                            class="block mt-1 w-full">
                        <x-input-error :messages="$errors->get('images')" class="mt-2" />
                        <x-input-error :messages="$errors->get('images.*')" class="mt-2" />
                    </div>

                    <!-- Existing Images Preview -->
                    @if($pledge->images)
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Images:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach(json_decode($pledge->images, true) as $img)
                                    <img src="{{ asset('storage/' . $img) }}" alt="Pledge Image"
                                        class="w-24 h-24 object-cover rounded border">
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Submit -->
                    <div class="flex items-center justify-between mt-6">
                        <x-primary-button>
                            {{ __('Update Pledge') }}
                        </x-primary-button>
                        <a href="{{ route('pledges.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @else
        <div class="text-red-600 font-semibold p-6">
            You are not authorized to edit this pledge.
        </div>
    @endcan
</x-app-layout>

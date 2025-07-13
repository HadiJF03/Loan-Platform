@can('create', App\Models\Pledge::class)
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create a New Pledge') }}
            </h2>
        </x-slot>

        <div class="py-10">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form method="POST" action="{{ route('pledges.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Category Select -->
                        <div class="mb-4">
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select name="category_id" id="category_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Requested Amount -->
                        <div class="mb-4">
                            <x-input-label for="requested_amount" :value="__('Requested Amount')" />
                            <x-text-input id="requested_amount" class="block mt-1 w-full" type="number" step="0.01" name="requested_amount" value="{{ old('requested_amount') }}" required />
                            <x-input-error :messages="$errors->get('requested_amount')" class="mt-2" />
                        </div>

                        <!-- Collateral Duration -->
                        <div class="mb-4">
                            <x-input-label for="collateral_duration" :value="__('Collateral Duration (Days)')" />
                            <x-text-input id="collateral_duration" class="block mt-1 w-full" type="number" name="collateral_duration" value="{{ old('collateral_duration') }}" required />
                            <x-input-error :messages="$errors->get('collateral_duration')" class="mt-2" />
                        </div>

                        <!-- Repayment Terms -->
                        <div class="mb-4">
                            <x-input-label for="repayment_terms" :value="__('Repayment Terms')" />
                            <textarea id="repayment_terms" name="repayment_terms" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white">{{ old('repayment_terms') }}</textarea>
                            <x-input-error :messages="$errors->get('repayment_terms')" class="mt-2" />
                        </div>

                        <!-- Images Upload -->
                        <div class="mb-4">
                            <x-input-label for="images" :value="__('Upload Item Images (optional)')" />
                            <input id="images" name="images[]" type="file" multiple accept="image/*" class="block mt-1 w-full">
                            <x-input-error :messages="$errors->get('images')" class="mt-2" />
                            <x-input-error :messages="$errors->get('images.*')" class="mt-2" />
                        </div>

                        <!-- Submit -->
                        <x-primary-button class="mt-4">
                            {{ __('Submit Pledge') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </x-app-layout>
@else
    <div class="text-red-600 font-semibold p-6">
        {{ __('You are not authorized to create a pledge.') }}
    </div>
@endcan

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Available Pledges') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Filters -->
            <form method="GET" action="{{ route('pledges.browse') }}" class="mb-6 bg-white p-4 rounded shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label for="category_id" class="block font-medium text-sm text-gray-700">Category</label>
                        <select name="category_id" id="category_id" class="form-select w-full mt-1">
                            <option value="">All</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="min_amount" class="block font-medium text-sm text-gray-700">Min Amount</label>
                        <input type="number" name="min_amount" id="min_amount" value="{{ request('min_amount') }}" class="form-input w-full mt-1" step="0.01" />
                    </div>

                    <div>
                        <label for="max_amount" class="block font-medium text-sm text-gray-700">Max Amount</label>
                        <input type="number" name="max_amount" id="max_amount" value="{{ request('max_amount') }}" class="form-input w-full mt-1" step="0.01" />
                    </div>

                    <div>
                        <label for="sort" class="block font-medium text-sm text-gray-700">Sort By</label>
                        <select name="sort" id="sort" class="form-select w-full mt-1">
                            <option value="">Newest</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                            <option value="high_amount" {{ request('sort') == 'high_amount' ? 'selected' : '' }}>Highest Amount</option>
                            <option value="low_amount" {{ request('sort') == 'low_amount' ? 'selected' : '' }}>Lowest Amount</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 w-full">
                            Filter
                        </button>
                        <a href="{{ route('pledges.browse') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 w-full text-center">
                            Reset
                        </a>
                    </div>
                </div>
            </form>

            <!-- Results -->
            @if($pledges->isEmpty())
                <div class="text-gray-500 text-center py-8">
                    {{ __('No pledges are currently available.') }}
                </div>
            @endif

            @foreach ($pledges as $pledge)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 mb-4">
                    <p><strong>Description:</strong> {{ $pledge->description }}</p>
                    <p><strong>Requested Amount:</strong> {{ number_format($pledge->requested_amount, 2) }} SAR</p>
                    <p><strong>Category:</strong> {{ $pledge->category->name ?? 'N/A' }}</p>
                    <p><strong>By:</strong> {{ $pledge->user->name ?? 'Unknown' }}</p>

                    @if($pledge->images)
                        <div class="mt-2 flex gap-2 flex-wrap">
                            @foreach($pledge->images as $img)
                                <img src="{{ asset('storage/' . $img) }}" alt="Pledge Image" class="w-32 h-32 object-cover rounded border" />
                            @endforeach
                        </div>
                    @endif

                    @can('create', [App\Models\Offer::class, $pledge])
                        <div class="mt-4">
                            <a href="{{ route('offers.create', $pledge->id) }}">
                                <x-primary-button>
                                    {{ __('Make an Offer') }}
                                </x-primary-button>
                            </a>
                        </div>
                    @endcan
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="mt-6">
                {{ $pledges->links() }}
            </div>

        </div>
    </div>
</x-app-layout>

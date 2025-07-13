<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight text-center">
            {{ __('Pledges Management') }}
        </h2>
    </x-slot>

    <div class="py-10 flex justify-center mt-6">
        <div class="min-w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="min-w-full bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <div class="min-w-full  overflow-x-auto">
                    <div class="px-6 py-4 bg-gray-700">
                        <form method="GET" action="{{ route('admin.pledges.index') }}" class="flex flex-wrap gap-4 items-center">
                            <!-- Category Filter -->
                            <div>
                                <label for="category_id" class="block text-sm text-white">Category</label>
                                <select name="category_id" id="category_id" class="text-black rounded p-1">
                                    <option value="">All</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Amount Filters -->
                            <div>
                                <label for="min_amount" class="block text-sm text-white">Min Amount</label>
                                <input type="number" name="min_amount" id="min_amount" value="{{ request('min_amount') }}" class="text-black rounded p-1" placeholder="0">
                            </div>
                            <div>
                                <label for="max_amount" class="block text-sm text-white">Max Amount</label>
                                <input type="number" name="max_amount" id="max_amount" value="{{ request('max_amount') }}" class="text-black rounded p-1" placeholder="10000">
                            </div>
                            <!-- Status Filter -->
                            <div>
                                <label for="status" class="block text-sm text-white">Status</label>
                                <select name="status" id="status" class="text-black rounded p-1">
                                    <option value="">All</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            <div class="mt-6">
                                <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600">Filter</button>
                                <a href="{{ route('admin.pledges.index') }}" class="ml-2 text-sm underline text-white">Reset</a>
                            </div>
                        </form>
                    </div>
                    <table class="min-w-full divide-y divide-gray-700 text-white">
                        <thead class="bg-gray-900 text-gray-300">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase">User</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase">Category</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase">Offers</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase">Created At</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-sm font-medium uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach ($pledges as $pledge)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $pledge->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $pledge->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $pledge->category->name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $pledge->requested_amount }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $pledge->offers->count() }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $pledge->created_at->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $pledge->status }}</td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <form action="{{ route('admin.pledges.delete', $pledge) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <x-danger-button>Delete</x-danger-button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

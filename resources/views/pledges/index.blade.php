<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pledges') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Create Button -->
            @can('create', \App\Models\Pledge::class)
                <div class="mb-6 flex justify-end">
                    <a href="{{ route('pledges.create') }}">
                        <x-primary-button>
                            {{ __('Create New Pledge') }}
                        </x-primary-button>
                    </a>
                </div>
            @endcan

            <!-- Pledges List -->
            @forelse ($pledges as $pledge)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 mb-4 flex flex-col md:flex-row md:justify-between md:items-center">
                    <div class="mb-4 md:mb-0 w-full">

                        <p><strong>Description:</strong> {{ $pledge->description }}</p>
                        <p><strong>Requested Amount:</strong> {{ number_format($pledge->requested_amount, 2) }} SAR</p>
                        <p><strong>Item Type:</strong> {{ $pledge->item_type }}</p>
                        <p><strong>Duration:</strong> {{ $pledge->collateral_duration }} days</p>
                        <p><strong>Repayment Terms:</strong> {{ $pledge->repayment_terms }}</p>
                        <p><strong>Status:</strong>
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold
                                @switch($pledge->status)
                                    @case('open') bg-green-100 text-green-800 @break
                                    @case('negotiating') bg-yellow-100 text-yellow-800 @break
                                    @case('finalized') bg-blue-100 text-blue-800 @break
                                    @case('withdrawn') bg-red-100 text-red-800 @break
                                    @default bg-gray-100 text-gray-800
                                @endswitch">
                                {{ ucfirst($pledge->status) }}
                            </span>
                        </p>
                        <p><strong>By:</strong> {{ $pledge->user->name ?? 'Unknown' }}</p>

                        @if($pledge->images)
                            <div class="mt-2 flex gap-2 flex-wrap">
                                @foreach(json_decode($pledge->images, true) as $img)
                                    <img src="{{ asset('storage/' . $img) }}" alt="Pledge Image" class="w-20 h-20 object-cover rounded border">
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4 flex-wrap justify-end">
                        @can('update', $pledge)
                            <a href="{{ route('pledges.edit', $pledge->id) }}">
                                <x-primary-button class="inline-flex items-center">
                                    {{ __('Edit') }}
                                </x-primary-button>
                            </a>
                        @endcan

                        @can('delete', $pledge)
                            <form action="{{ route('pledges.destroy', $pledge->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this pledge?');">
                                @csrf
                                @method('DELETE')
                                <x-danger-button class="inline-flex items-center">
                                    {{ __('Delete') }}
                                </x-danger-button>
                            </form>
                        @endcan
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">No pledges found.</p>
            @endforelse

            <!-- Pagination -->
            <div class="mt-6">
                {{ method_exists($pledges, 'links') ? $pledges->links() : '' }}
            </div>

        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Offers') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($offers->count())
                @foreach ($offers as $offer)
                    @include('components.offer-tree', ['offer' => $offer, 'level' => 0])
                @endforeach

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $offers->links() }}
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 p-6 rounded shadow text-center text-gray-600 dark:text-gray-300">
                    {{ __('You have not made any offers yet.') }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>

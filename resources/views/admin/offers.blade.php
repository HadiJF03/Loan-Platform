<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight text-center">
            {{ __('Offers Management') }}
        </h2>
    </x-slot>

    <div class="py-10 flex justify-center mt-6">
        <div class="min-w-full max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="min-w-full bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <div class="min-w-full overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-700 text-white">
                        <thead class="bg-gray-900 text-gray-300">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase">By</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase">Parent</th>
                                <th class="px-6 py-3 text-right text-sm font-medium uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach ($offers as $offer)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $offer->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $offer->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $offer->offer_amount }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $offer->status }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $offer->parentOffer?->id ?? '-' }}</td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <form method="POST" action="{{ route('admin.offers.delete', $offer) }}" onsubmit="return confirm('Are you sure?');">
                                            @csrf @method('DELETE')
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

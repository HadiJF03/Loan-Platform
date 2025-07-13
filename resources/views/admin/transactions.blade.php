<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight text-center">
            {{ __('Transactions Management') }}
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
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase">Pledger</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase">Pledgee</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase">Commission</th>
                                <th class="px-6 py-3 text-right text-sm font-medium uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ optional($transaction->pledge->user)->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ optional($transaction->offer->user)->name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($transaction->payment_status) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ number_format($transaction->commission, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <form method="POST" action="{{ route('admin.transactions.delete', $transaction) }}" onsubmit="return confirm('Are you sure you want to delete this transaction?');">
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

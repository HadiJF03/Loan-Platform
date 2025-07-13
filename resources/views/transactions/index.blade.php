<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Your Transactions') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
            @if(session('success'))
                <div class="mb-4 text-green-700 bg-green-100 p-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 text-red-700 bg-red-100 p-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @forelse ($transactions as $transaction)
                <x-transaction-card :transaction="$transaction" />
            @empty
                <div class="bg-white dark:bg-gray-800 shadow p-6 rounded">
                    <p class="text-gray-600 dark:text-gray-300">You have no transactions yet.</p>
                </div>
            @endforelse

            <div class="mt-6">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

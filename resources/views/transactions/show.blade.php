<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transaction Details') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow p-6 rounded space-y-4">

                <!-- Transaction Overview -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Overview</h3>
                    <p><strong>Pledge ID:</strong> {{ $transaction->pledge_id }}</p>
                    <p><strong>Offer ID:</strong> {{ $transaction->offer_id }}</p>
                    <p><strong>Start Date:</strong> {{ $transaction->start_date->format('Y-m-d') }}</p>
                    <p><strong>End Date:</strong> {{ $transaction->end_date->format('Y-m-d') }}</p>
                    <p><strong>Duration:</strong> {{ $transaction->start_date->diffInDays($transaction->end_date) }} days</p>
                    <p><strong>Collateral Status:</strong> {{ ucfirst($transaction->collateral_status) }}</p>
                    <p><strong>Payment Status:</strong> {{ ucfirst($transaction->payment_status) }}</p>
                    <p><strong>Commission:</strong> {{ number_format($transaction->commission, 2) }} SAR</p>
                </div>

                <!-- Methods -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Details</h3>
                    <p><strong>Payment Method:</strong> {{ $transaction->payment_method }}</p>
                    <p><strong>Delivery Method:</strong> {{ $transaction->delivery_method }}</p>
                </div>

                <!-- Action -->
                @can('update', $transaction)
                    @if ($transaction->collateral_status !== 'closed')
                        <form method="POST" action="{{ route('transactions.complete', $transaction) }}" onsubmit="return confirm('Are you sure you want to complete this transaction?');">
                            @csrf
                            <x-primary-button>
                                {{ __('Mark as Completed') }}
                            </x-primary-button>
                        </form>
                    @endif
                @endcan

                <div class="mt-4">
                    <a href="{{ route('transactions.index') }}" class="text-blue-600 hover:underline">
                        &larr; Back to transactions
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

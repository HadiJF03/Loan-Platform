@props(['transaction'])

@php
    $user = auth()->user();
    $isPledger = $user->id === $transaction->pledge->user_id;

    // Traverse to root offer (original offer creator)
    $offer = $transaction->offer;
    while ($offer->parentOffer) {
        $offer = $offer->parentOffer;
    }
    $isPledgee = $user->id === $offer->user_id;
@endphp

@can('view', $transaction)
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 border mb-4">
        <div class="mb-2">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                Transaction #{{ $transaction->id }}
            </h3>
            <p class="text-sm text-gray-500">
                Created: {{ $transaction->created_at->format('d M Y, H:i') }}
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4 text-sm text-gray-800 dark:text-gray-200">
            <div>
                <p><strong>Pledge:</strong> {{ $transaction->pledge->title ?? '—' }}</p>
                <p><strong>Offer Amount:</strong> {{ number_format($transaction->offer->offer_amount, 2) }} SAR</p>
                <p><strong>Duration:</strong> {{ $transaction->offer->duration }} days</p>
                <p><strong>Commission:</strong> {{ number_format($transaction->commission, 2) }} SAR</p>
            </div>

            <div>
                <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($transaction->start_date)->format('d M Y') }}</p>
                <p><strong>End Date:</strong> {{ \Carbon\Carbon::parse($transaction->end_date)->format('d M Y') }}</p>
                <p><strong>Payment Method:</strong> {{ $transaction->payment_method ?? 'Not specified' }}</p>
                <p><strong>Delivery Method:</strong> {{ $transaction->delivery_method ?? 'Not specified' }}</p>
            </div>
        </div>

        <div class="mt-4 text-sm text-gray-800 dark:text-gray-200">
            <p>
                <strong>Collateral Status:</strong>
                <span class="inline-block px-2 py-1 rounded text-white text-xs
                    @if($transaction->collateral_status === 'active') bg-green-600
                    @elseif($transaction->collateral_status === 'closed') bg-gray-600
                    @elseif($transaction->collateral_status === 'delayed') bg-yellow-500
                    @else bg-red-600 @endif">
                    {{ ucfirst($transaction->collateral_status) }}
                </span>
            </p>

            <p class="mt-2">
                <strong>Payment Status:</strong>
                <span class="inline-block px-2 py-1 rounded text-white text-xs
                    @if($transaction->payment_status === 'paid') bg-green-600
                    @elseif($transaction->payment_status === 'overdue') bg-red-600
                    @else bg-yellow-500 @endif">
                    {{ ucfirst($transaction->payment_status) }}
                </span>
            </p>

            <p class="mt-2">
                <strong>Collateral Confirmed:</strong>
                Pledger: {{ $transaction->collateral_confirmed_by_pledger ? '✅' : '❌' }},
                Pledgee: {{ $transaction->collateral_confirmed_by_pledgee ? '✅' : '❌' }}
            </p>

            <p class="mt-1">
                <strong>Payment Confirmed:</strong>
                Pledger: {{ $transaction->payment_confirmed_by_pledger ? '✅' : '❌' }},
                Pledgee: {{ $transaction->payment_confirmed_by_pledgee ? '✅' : '❌' }}
            </p>
        </div>

        @can('update', $transaction)
            <div class="mt-4 space-y-2">
                {{-- Mark as Completed --}}
                <form method="POST" action="{{ route('transactions.complete', $transaction->id) }}">
                    @csrf
                    <x-primary-button type="submit" onclick="return confirm('Mark this transaction as completed?')">
                        Mark as Completed
                    </x-primary-button>
                </form>

                {{-- Confirm Collateral --}}
                @if (!($transaction->collateral_confirmed_by_pledger && $transaction->collateral_confirmed_by_pledgee))
                    @if ($isPledger && !$transaction->collateral_confirmed_by_pledger)
                        <form method="POST" action="{{ route('transactions.confirmCollateral', $transaction->id) }}">
                            @csrf
                            <x-secondary-button type="submit" onclick="return confirm('Confirm that you sent the collateral?')">
                                Confirm Collateral Sent
                            </x-secondary-button>
                        </form>
                    @elseif ($isPledgee && $transaction->collateral_confirmed_by_pledger && !$transaction->collateral_confirmed_by_pledgee)
                        <form method="POST" action="{{ route('transactions.confirmCollateral', $transaction->id) }}">
                            @csrf
                            <x-secondary-button type="submit" onclick="return confirm('Confirm that you received the collateral?')">
                                Confirm Collateral Received
                            </x-secondary-button>
                        </form>
                    @endif
                @endif

                {{-- Confirm Payment --}}
                @if (!($transaction->payment_confirmed_by_pledger && $transaction->payment_confirmed_by_pledgee))
                    @if ($isPledgee && !$transaction->payment_confirmed_by_pledgee)
                        <form method="POST" action="{{ route('transactions.confirmPayment', $transaction->id) }}">
                            @csrf
                            <x-secondary-button type="submit" onclick="return confirm('Confirm that you sent the payment?')">
                                Confirm Payment Sent
                            </x-secondary-button>
                        </form>
                    @elseif ($isPledger && $transaction->payment_confirmed_by_pledgee && !$transaction->payment_confirmed_by_pledger)
                        <form method="POST" action="{{ route('transactions.confirmPayment', $transaction->id) }}">
                            @csrf
                            <x-secondary-button type="submit" onclick="return confirm('Confirm that you received the payment?')">
                                Confirm Payment Received
                            </x-secondary-button>
                        </form>
                    @endif
                @endif
            </div>
        @endcan
    </div>
@endcan

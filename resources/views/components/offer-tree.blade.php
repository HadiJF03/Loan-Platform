<div class="ml-{{ $level * 4 }} bg-white p-4 mb-4 rounded shadow border {{ $offer->status === 'accepted' ? 'border-green-500' : 'border-gray-300' }}">
    <p><strong>Offer Amount:</strong> {{ number_format($offer->offer_amount, 2) }} SAR</p>
    <p><strong>Duration:</strong> {{ $offer->duration }} days</p>
    <p><strong>Status:</strong>
        <span class="px-2 py-1 rounded text-white text-sm
            @if($offer->status === 'accepted') bg-green-600
            @elseif($offer->status === 'rejected') bg-red-500
            @elseif($offer->status === 'amended') bg-orange-500
            @else bg-yellow-500 @endif">
            {{ ucfirst($offer->status) }}
        </span>
    </p>
    <p><strong>By:</strong> {{ $offer->user->name }}</p>

    <div class="mt-3 flex flex-wrap gap-2">
        @php $isLatest = $offer->amendments->isEmpty(); @endphp

        @if ($isLatest)
            @can('update', $offer)
                <a href="{{ route('offers.edit', $offer->id) }}">
                    <x-primary-button type="button">
                        {{ __('Edit') }}
                    </x-primary-button>
                </a>
            @elsecan('amend', $offer)
                <a href="{{ route('offers.amend.form', $offer->id) }}">
                    <x-primary-button type="button">
                        {{ __('Amend') }}
                    </x-primary-button>
                </a>
                @can('manage', $offer)
                    @if ($offer->status !== 'accepted')
                        <form action="{{ route('offers.accept', $offer->id) }}" method="POST" onsubmit="return confirm('Accept this offer?');">
                            @csrf
                            <x-primary-button class="ml-2" type="submit">
                                {{ __('Accept') }}
                            </x-primary-button>
                        </form>
                    @endif
                @endcan
            @endcan
        @endif

        @can('delete', $offer)
            <form action="{{ route('offers.destroy', $offer->id) }}" method="POST" onsubmit="return confirm('Withdraw this offer?');">
                @csrf
                @method('DELETE')
                <x-danger-button type="submit">
                    {{ __('Withdraw') }}
                </x-danger-button>
            </form>
        @endcan
    </div>

    @if ($offer->terms)
        <div class="mt-3">
            <p><strong>Terms:</strong> {{ $offer->terms }}</p>
        </div>
    @endif

    <!-- Show amendment chain -->
    @foreach ($offer->amendments as $child)
        @include('components.offer-tree', ['offer' => $child, 'level' => $level + 1])
    @endforeach
</div>

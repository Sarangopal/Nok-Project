<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            🎁 Exclusive Offers for Members
        </x-slot>

        @if($member && $member->renewal_status === 'approved')
            @if($offers && $offers->count() > 0)
                <div class="space-y-3">
                    @foreach($offers as $offer)
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-base text-gray-900 dark:text-white">
                                        {{ $offer->offer_title }}
                                    </h4>
                                    
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $offer->offer_body }}
                                    </p>
                                    
                                    @if($offer->promo_code)
                                        <div class="mt-2">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">Promo Code:</span>
                                            <code class="ml-2 inline-block px-2 py-1 bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-400 rounded font-mono text-sm font-bold">
                                                {{ $offer->promo_code }}
                                            </code>
                                        </div>
                                    @endif
                                    
                                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                        📅 {{ \Carbon\Carbon::parse($offer->starts_at)->format('d M Y') }} - {{ \Carbon\Carbon::parse($offer->ends_at)->format('d M Y') }}
                                    </div>
                                </div>
                                
                                @if($offer->active)
                                    <x-filament::badge color="success" size="sm">
                                        Active
                                    </x-filament::badge>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-gray-400 dark:text-gray-600 mb-2">
                        <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        No exclusive offers available at the moment.
                    </p>
                </div>
            @endif
        @elseif($member && $member->renewal_status !== 'approved')
            <div class="p-3 rounded-md border border-yellow-200 text-yellow-800 bg-yellow-50 dark:border-yellow-800 dark:text-yellow-200 dark:bg-yellow-900/20">
                Exclusive offers are available only for approved members. Your status: <strong>{{ ucfirst($member->renewal_status ?? 'pending') }}</strong>
            </div>
        @else
            <p class="text-sm text-gray-500 dark:text-gray-400">Please log in to view exclusive offers.</p>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>


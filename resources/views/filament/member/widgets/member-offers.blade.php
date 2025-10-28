<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-4">
            <h3 class="text-lg font-semibold">🎁 Exclusive Offers</h3>

            @if($offers->count() > 0)
                <div class="space-y-3">
                    @foreach($offers as $offer)
                        <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:shadow-md transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-base mb-2">{{ $offer->offer_title }}</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                        {!! nl2br(e(Str::limit($offer->offer_body, 150))) !!}
                                    </p>
                                    
                                    @if($offer->promo_code)
                                        <div class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-md text-sm font-mono">
                                            🎟️ {{ $offer->promo_code }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                    </svg>
                    <p>No exclusive offers available at the moment.</p>
                    <p class="text-sm mt-1">Check back later for new deals!</p>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>


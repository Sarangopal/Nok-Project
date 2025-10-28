<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            💳 Membership Card
        </x-slot>

        @if($member && $member->renewal_status === 'approved')
            <div class="space-y-4">
                @if($member->card_valid_until && $member->card_valid_until->isPast())
                    <div class="p-3 rounded-md border border-red-200 text-red-800 bg-red-50 dark:border-red-800 dark:text-red-200 dark:bg-red-900/20">
                        Your membership card has expired. Please request a renewal.
                    </div>
                @elseif($member->card_valid_until && $member->card_valid_until->diffInDays(now()) <= 30)
                    <div class="p-3 rounded-md border border-yellow-200 text-yellow-800 bg-yellow-50 dark:border-yellow-800 dark:text-yellow-200 dark:bg-yellow-900/20">
                        Your membership card will expire soon ({{ $member->card_valid_until->diffForHumans() }}).
                    </div>
                @endif

                <div class="text-center">
                    <x-filament::button
                        tag="a"
                        :href="$downloadUrl"
                        icon="heroicon-o-arrow-down-tray"
                        color="primary"
                        size="lg"
                    >
                        Download PDF
                    </x-filament::button>
                </div>
            </div>
        @elseif($member && $member->renewal_status !== 'approved')
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Membership card will be available once your membership is approved.
            </p>
        @else
            <p class="text-sm text-gray-500 dark:text-gray-400">No membership card information available.</p>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>


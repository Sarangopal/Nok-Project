<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Profile Overview
        </x-slot>

        @if($member)
            <div class="space-y-4">
                <dl class="grid grid-cols-1 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">NOK ID:</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $member->nok_id ?? 'N/A' }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email:</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $member->email ?? 'N/A' }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Mobile:</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $member->mobile ?? 'N/A' }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Address:</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $member->address ?? 'N/A' }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Joining Date:</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $member->doj ? $member->doj->format('d M Y') : 'N/A' }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Renewal Date:</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $member->card_valid_until ? $member->card_valid_until->format('d M Y') : 'N/A' }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status:</dt>
                        <dd class="mt-1">
                            <x-filament::badge :color="$member->renewal_status === 'approved' ? 'success' : ($member->renewal_status === 'pending' ? 'warning' : 'danger')">
                                {{ ucfirst($member->renewal_status ?? 'Unknown') }}
                            </x-filament::badge>
                        </dd>
                    </div>
                </dl>

                @php
                    $daysLeft = $member->card_valid_until ? now()->diffInDays($member->card_valid_until, false) : null;
                    $isExpired = isset($daysLeft) ? $daysLeft < 0 : false;
                    $isExpiringSoon = isset($daysLeft) ? ($daysLeft >= 0 && $daysLeft <= 30) : false;
                    $isPending = ($member->renewal_status === 'pending') && !empty($member->renewal_requested_at);
                @endphp

                <div class="flex flex-col sm:flex-row gap-3">
                    <x-filament::button tag="a" :href="route('filament.member.pages.edit-profile')" color="primary">
                        Edit Profile
                    </x-filament::button>

                    @if($isPending)
                        <x-filament::button disabled color="info">
                            Pending Renewal
                        </x-filament::button>
                    @elseif(($isExpired || $isExpiringSoon))
                        <form method="POST" action="{{ route('member.renewal.request') }}">
                            @csrf
                            <x-filament::button type="submit" :color="$isExpired ? 'danger' : 'warning'">
                                {{ $isExpired ? 'Request Renewal' : 'Request Early Renewal' }}
                            </x-filament::button>
                        </form>
                    @else
                        <x-filament::button color="gray" disabled>
                            Renewal not available yet
                        </x-filament::button>
                    @endif
                </div>
            </div>
        @else
            <p class="text-sm text-gray-500 dark:text-gray-400">No profile information available.</p>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>


<x-filament-widgets::widget>
    <x-filament::section>

        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold">My Membership Card</h3>
                
                @if($status === 'active')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        ✓ Active
                    </span>
                @elseif($status === 'expiring_soon')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                        ⚠ Expiring Soon
                    </span>
                @elseif($status === 'expired')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                        ✕ Expired
                    </span>
                @elseif($status === 'pending')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                        ⏳ Pending Approval
                    </span>
                @endif
            </div>

            @if($member)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Member Name</p>
                        <p class="font-semibold">{{ $member->memberName ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">NOK ID</p>
                        <p class="font-semibold">{{ $member->nok_id ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Civil ID</p>
                        <p class="font-semibold">{{ $member->civil_id ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Valid Until</p>
                        <p class="font-semibold">
                            @if($member->card_valid_until)
                                {{ $member->card_valid_until->format('M d, Y') }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>

                @if($status === 'active' || $status === 'expiring_soon')
                    <div class="flex gap-2">
                        <a href="{{ route('membership.card.download', $member->id) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            📥 Download Card
                        </a>
                        
                        @if($member->qr_code_path && file_exists(public_path('storage/' . $member->qr_code_path)))
                            <a href="{{ route('verify', ['civil_id' => $member->civil_id]) }}" 
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                                🔍 Verify Card
                            </a>
                        @endif
                    </div>
                @endif

                @if($status === 'expired' || $status === 'expiring_soon')
                    @if(!$member->renewal_requested_at || $member->renewal_status === 'rejected')
                        <form action="{{ route('member.renewal.request') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                🔄 Request Renewal
                            </button>
                        </form>
                    @else
                        <div class="p-4 bg-blue-50 dark:bg-blue-900 rounded-lg text-center">
                            <p class="text-blue-800 dark:text-blue-200">
                                ⏳ Renewal request submitted on {{ $member->renewal_requested_at->format('M d, Y') }}
                            </p>
                        </div>
                    @endif
                @endif
            @else
                <p class="text-gray-500">No membership information available.</p>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>


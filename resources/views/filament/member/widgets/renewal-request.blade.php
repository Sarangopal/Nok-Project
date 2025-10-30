<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            🔄 Membership Renewal
        </x-slot>

        @if($member)
            <div class="space-y-4 renewal-widget">
                <style>
                    /* Constrain any SVGs inside this widget to avoid oversized icons from global CSS */
                    .renewal-widget svg { width: 1.25rem; height: 1.25rem; flex-shrink: 0; }
                    .renewal-widget .fi-btn svg { width: 1rem; height: 1rem; }
                </style>
                @if($isPending)
                    {{-- Renewal request pending --}}
                    <div class="p-4 rounded-lg border-2 border-blue-200 bg-blue-50 dark:border-blue-800 dark:bg-blue-900/20">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-blue-900 dark:text-blue-100">Renewal Request Pending</h3>
                                <p class="mt-1 text-sm text-blue-700 dark:text-blue-300">
                                    Your renewal request has been submitted and is awaiting admin approval.
                                </p>
                                <p class="mt-2 text-xs text-blue-600 dark:text-blue-400">
                                    Requested on: {{ is_string($member->renewal_requested_at) ? $member->renewal_requested_at : $member->renewal_requested_at->format('M d, Y \a\t H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif($showButton)
                    {{-- Show renewal button --}}
                    <div class="space-y-3">
                        @if($isExpired)
                            <div class="p-3 rounded-md border border-red-200 text-red-800 bg-red-50 dark:border-red-800 dark:text-red-200 dark:bg-red-900/20">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <span class="font-medium">Your membership has expired!</span>
                                </div>
                                <p class="mt-1 text-sm">
                                    Please request a renewal to continue accessing member benefits.
                                </p>
                            </div>
                        @elseif($isExpiringSoon)
                            <div class="p-3 rounded-md border border-yellow-200 text-yellow-800 bg-yellow-50 dark:border-yellow-800 dark:text-yellow-200 dark:bg-yellow-900/20">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="font-medium">Your membership expires soon!</span>
                                </div>
                                <p class="mt-1 text-sm">
                                    Only {{ abs($daysLeft) }} days remaining. Request an early renewal now.
                                </p>
                            </div>
                        @endif

                    {{-- Profile confirmation notice --}}
                    <div class="p-3 rounded-md border border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800/50">
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            <span class="font-medium">Before submitting:</span> Please ensure your profile information is up to date. 
                            Your current details will be reviewed for renewal approval.
                        </p>
                    </div>

                    {{-- Renewal request form --}}
                    <form method="POST" action="{{ route('member.renewal.request') }}" enctype="multipart/form-data" class="mt-3 space-y-3">
                        @csrf
                        
                        {{-- Payment Proof Upload --}}
                        <div class="space-y-2">
                            <label for="payment_proof" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Payment Proof <span class="text-red-600">*</span>
                            </label>
                            <input 
                                type="file" 
                                name="payment_proof" 
                                id="payment_proof" 
                                accept="image/*"
                                required
                                class="block w-full text-sm text-gray-900 dark:text-gray-100 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none"
                            />
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Please upload proof of payment (image format)
                            </p>
                            @error('payment_proof')
                                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <x-filament::button
                            type="submit"
                            :color="$buttonColor"
                            size="lg"
                            class="w-full"
                        >
                            {{ $buttonText }}
                        </x-filament::button>
                    </form>
                    </div>
                @else
                    {{-- Membership is active --}}
                    <div class="p-4 rounded-lg border-2 border-green-200 bg-green-50 dark:border-green-800 dark:bg-green-900/20">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-green-900 dark:text-green-100">Membership Active</h3>
                                <p class="mt-1 text-sm text-green-700 dark:text-green-300">
                                    Your membership is currently active and valid until {{ is_string($member->card_valid_until) ? $member->card_valid_until : $member->card_valid_until->format('M d, Y') }}.
                                </p>
                                @if($daysLeft > 30)
                                    <p class="mt-2 text-xs text-green-600 dark:text-green-400">
                                        {{ $daysLeft }} days remaining
                                    </p>
                                    <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">
                                        <strong>Note:</strong> Renewal option will become available when your membership is within 30 days of expiry.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Show success message if exists --}}
                @if(session('status'))
                    <div class="p-3 rounded-md border border-green-200 text-green-800 bg-green-50 dark:border-green-800 dark:text-green-200 dark:bg-green-900/20">
                        {{ session('status') }}
                    </div>
                @endif
            </div>
        @else
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Unable to load membership information.
            </p>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>


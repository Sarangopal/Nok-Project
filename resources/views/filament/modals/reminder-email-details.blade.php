@php
    $daysLeft = (int) now()->diffInDays($record->card_valid_until, false);
@endphp

<div class="space-y-6">
    {{-- Header Summary Card with Gradient --}}
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-primary-500 via-primary-600 to-primary-700 p-6 shadow-lg dark:from-primary-600 dark:via-primary-700 dark:to-primary-800">
        <div class="absolute right-0 top-0 -mr-10 -mt-10 h-40 w-40 rounded-full bg-white/10"></div>
        <div class="absolute bottom-0 left-0 -mb-10 -ml-10 h-32 w-32 rounded-full bg-white/5"></div>
        
        <div class="relative">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white">{{ $record->member_name }}</h3>
                            <p class="text-sm text-primary-100">{{ $record->registration->nok_id ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4 text-sm text-primary-50">
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $record->email }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $record->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="text-right">
                    @if($record->status === 'sent')
                        <div class="inline-flex items-center gap-2 rounded-full bg-green-500/20 px-4 py-2 backdrop-blur-sm">
                            <div class="h-2 w-2 rounded-full bg-green-400 animate-pulse"></div>
                            <span class="text-sm font-semibold text-white">Sent Successfully</span>
                        </div>
                    @else
                        <div class="inline-flex items-center gap-2 rounded-full bg-red-500/20 px-4 py-2 backdrop-blur-sm">
                            <div class="h-2 w-2 rounded-full bg-red-400"></div>
                            <span class="text-sm font-semibold text-white">Failed</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Two Column Layout --}}
    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Contact Information --}}
        <div class="fi-section overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4 dark:border-white/5 dark:bg-white/5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-500/10">
                        <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <h4 class="text-base font-semibold text-gray-900 dark:text-white">Contact Information</h4>
                </div>
            </div>
            
            <div class="p-6">
                <dl class="space-y-4">
                    <div class="group">
                        <dt class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Mobile</dt>
                        <dd class="flex items-center gap-2 text-sm font-medium text-gray-900 dark:text-white">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            {{ $record->registration->mobile ?? 'N/A' }}
                        </dd>
                    </div>
                    
                    <div class="group">
                        <dt class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">WhatsApp</dt>
                        <dd class="flex items-center gap-2 text-sm font-medium text-gray-900 dark:text-white">
                            <svg class="h-4 w-4 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            {{ $record->registration->whatsapp ?? 'N/A' }}
                        </dd>
                    </div>
                    
                    <div class="group">
                        <dt class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Department</dt>
                        <dd class="flex items-center gap-2 text-sm font-medium text-gray-900 dark:text-white">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            {{ $record->registration->department ?? 'N/A' }}
                        </dd>
                    </div>
                    
                    <div class="group">
                        <dt class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Institution</dt>
                        <dd class="flex items-center gap-2 text-sm font-medium text-gray-900 dark:text-white">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            {{ $record->registration->institution ?? 'N/A' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Reminder Details --}}
        <div class="fi-section overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4 dark:border-white/5 dark:bg-white/5">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-500/10">
                        <svg class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <h4 class="text-base font-semibold text-gray-900 dark:text-white">Reminder Details</h4>
                </div>
            </div>
            
            <div class="p-6">
                <dl class="space-y-4">
                    <div>
                        <dt class="mb-2 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Reminder Type</dt>
                        <dd>
                            @php
                                $badgeConfig = match($record->days_before_expiry) {
                                    0 => ['color' => 'red', 'icon' => '⚠️', 'text' => 'Expired Today'],
                                    1 => ['color' => 'orange', 'icon' => '🔔', 'text' => '1 Day Before'],
                                    7 => ['color' => 'yellow', 'icon' => '📅', 'text' => '7 Days Before'],
                                    15 => ['color' => 'blue', 'icon' => '📌', 'text' => '15 Days Before'],
                                    30 => ['color' => 'green', 'icon' => '✅', 'text' => '30 Days Before'],
                                    default => ['color' => 'gray', 'icon' => '📧', 'text' => $record->days_before_expiry . ' Days'],
                                };
                            @endphp
                            <div class="inline-flex items-center gap-2 rounded-lg bg-{{ $badgeConfig['color'] }}-50 px-4 py-2.5 ring-1 ring-{{ $badgeConfig['color'] }}-200 dark:bg-{{ $badgeConfig['color'] }}-500/10 dark:ring-{{ $badgeConfig['color'] }}-500/20">
                                <span class="text-base">{{ $badgeConfig['icon'] }}</span>
                                <span class="text-sm font-semibold text-{{ $badgeConfig['color'] }}-700 dark:text-{{ $badgeConfig['color'] }}-400">{{ $badgeConfig['text'] }}</span>
                            </div>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="mb-2 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Card Expiry Date</dt>
                        <dd class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-500/10">
                                <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-xl font-bold text-gray-900 dark:text-white">{{ $record->card_valid_until->format('d M Y') }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $record->card_valid_until->diffForHumans() }}</div>
                            </div>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="mb-2 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">Expiry Status</dt>
                        <dd>
                            @if($daysLeft < 0)
                                <div class="flex items-center gap-3 rounded-lg bg-red-50 p-3 dark:bg-red-500/10">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-500/20">
                                        <svg class="h-4 w-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-sm font-semibold text-red-700 dark:text-red-400">Expired</div>
                                        <div class="text-xs text-red-600 dark:text-red-400/80">{{ abs($daysLeft) }} days ago</div>
                                    </div>
                                </div>
                            @elseif($daysLeft === 0)
                                <div class="flex items-center gap-3 rounded-lg bg-yellow-50 p-3 dark:bg-yellow-500/10">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-500/20">
                                        <svg class="h-4 w-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-sm font-semibold text-yellow-700 dark:text-yellow-400">Expires Today</div>
                                        <div class="text-xs text-yellow-600 dark:text-yellow-400/80">Immediate action required</div>
                                    </div>
                                </div>
                            @elseif($daysLeft <= 7)
                                <div class="flex items-center gap-3 rounded-lg bg-orange-50 p-3 dark:bg-orange-500/10">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-orange-100 dark:bg-orange-500/20">
                                        <svg class="h-4 w-4 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-sm font-semibold text-orange-700 dark:text-orange-400">Expiring Soon</div>
                                        <div class="text-xs text-orange-600 dark:text-orange-400/80">{{ $daysLeft }} days remaining</div>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center gap-3 rounded-lg bg-green-50 p-3 dark:bg-green-500/10">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-green-100 dark:bg-green-500/20">
                                        <svg class="h-4 w-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-sm font-semibold text-green-700 dark:text-green-400">Active</div>
                                        <div class="text-xs text-green-600 dark:text-green-400/80">{{ $daysLeft }} days remaining</div>
                                    </div>
                                </div>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    @if($record->error_message)
    {{-- Error Alert --}}
    <div class="overflow-hidden rounded-xl border-l-4 border-red-500 bg-red-50 dark:bg-red-500/10">
        <div class="p-6">
            <div class="flex items-start gap-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-red-100 dark:bg-red-500/20">
                    <svg class="h-5 w-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h5 class="mb-1 text-sm font-semibold text-red-800 dark:text-red-300">Error Occurred</h5>
                    <p class="text-sm font-mono text-red-700 dark:text-red-400">{{ $record->error_message }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

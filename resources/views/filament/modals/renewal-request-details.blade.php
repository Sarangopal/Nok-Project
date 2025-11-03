<div style="padding: 1.5rem;">
    {{-- Payment Proof --}}
    <div style="background-color: lightblue; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem;">
        <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.75rem; display: flex; align-items: center;">
            <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            💳 Payment Proof
        </h3>
        @if($record->renewal_payment_proof)
            <div style="display: flex; justify-content: center; flex-direction: column; align-items: center;">
                <a href="{{ asset('storage/' . $record->renewal_payment_proof) }}" target="_blank" style="text-decoration: none;">
                    <img src="{{ asset('storage/' . $record->renewal_payment_proof) }}" 
                         alt="Payment Proof" 
                         style="max-width: 100%; height: auto; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border: 2px solid #e5e7eb; cursor: pointer; max-height: 400px;">
                </a>
                <p style="text-align: center; font-size: 0.875rem; color: #6b7280; margin-top: 0.75rem;">Click to view full size</p>
            </div>
        @else
            <div style="display: flex; align-items: center; justify-content: center; height: 10rem; background-color: #f3f4f6; border-radius: 0.5rem;">
                <div style="text-align: center;">
                    <p style="margin-top: 0.5rem; color: #6b7280; font-weight: 500;">No payment proof uploaded</p>
                </div>
            </div>
        @endif
    </div>

    {{-- Updated Member Details --}}
    <div style="background-color: #f0fdf4; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem;">
        <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.75rem; display: flex; align-items: center;">
            <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            📝 Updated Details from User
        </h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
            <div>
                <p style="font-size: 0.875rem; color: #6b7280;">Full Name</p>
                <p style="font-weight: 500; color: #111827;">{{ $record->memberName ?? 'N/A' }}</p>
            </div>
            <div>
                <p style="font-size: 0.875rem; color: #6b7280;">Email</p>
                <p style="font-weight: 500; color: #111827;">{{ $record->email ?? 'N/A' }}</p>
            </div>
            <div>
                <p style="font-size: 0.875rem; color: #6b7280;">Mobile</p>
                <p style="font-weight: 500; color: #111827;">{{ $record->mobile ?? 'N/A' }}</p>
            </div>
            <div>
                <p style="font-size: 0.875rem; color: #6b7280;">WhatsApp</p>
                <p style="font-weight: 500; color: #111827;">{{ $record->whatsapp ?? 'N/A' }}</p>
            </div>
            <div style="grid-column: span 2;">
                <p style="font-size: 0.875rem; color: #6b7280;">Address</p>
                <p style="font-weight: 500; color: #111827;">{{ $record->address ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    {{-- Request Information --}}
    <div style="background-color: #f9fafb; border-radius: 0.5rem; padding: 1rem;">
        <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.75rem; display: flex; align-items: center;">
            <svg style="width: 1.25rem; height: 1.25rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            📋 Request Info
        </h3>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
            <div>
                <p style="font-size: 0.875rem; color: #6b7280;">NOK ID</p>
                <p style="font-weight: 500; color: #111827;">{{ $record->nok_id ?? 'N/A' }}</p>
            </div>
            <div>
                <p style="font-size: 0.875rem; color: #6b7280;">Civil ID</p>
                <p style="font-weight: 500; color: #111827;">{{ $record->civil_id ?? 'N/A' }}</p>
            </div>
            <div>
                <p style="font-size: 0.875rem; color: #6b7280;">Requested On</p>
                <p style="font-weight: 500; color: #111827;">
                    {{ $record->renewal_requested_at ? (is_string($record->renewal_requested_at) ? \Carbon\Carbon::parse($record->renewal_requested_at)->format('d M Y, h:i A') : $record->renewal_requested_at->format('d M Y, h:i A')) : 'N/A' }}
                </p>
            </div>
            <div>
                <p style="font-size: 0.875rem; color: #6b7280;">Status</p>
                <p>
                    <span style="display: inline-flex; align-items: center; padding: 0.25rem 0.625rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; {{ $record->renewal_status === 'pending' ? 'background-color: #fef3c7; color: #92400e;' : 'background-color: #d1fae5; color: #065f46;' }}">
                        {{ ucfirst($record->renewal_status) }}
                    </span>
                </p>
            </div>
            <div>
                <p style="font-size: 0.875rem; color: #6b7280;">Current Card Expiry</p>
                <p style="font-weight: 500; color: #111827;">
                    {{ $record->card_valid_until ? (is_string($record->card_valid_until) ? \Carbon\Carbon::parse($record->card_valid_until)->format('d M Y') : $record->card_valid_until->format('d M Y')) : 'N/A' }}
                </p>
            </div>
        </div>
    </div>
</div>


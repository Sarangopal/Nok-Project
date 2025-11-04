{{-- Renewal Request Details Modal with Inline Styles (Filament Compatible) --}}
<div style="padding: 1.5rem; background-color: #f9fafb; font-family: system-ui, -apple-system, sans-serif;">
    
    {{-- Payment Proof Section --}}
    <div style="background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%); border-radius: 1rem; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 2px solid #bfdbfe;">
        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem; color: #1e40af; display: flex; align-items: center;">
            <svg style="width: 1.5rem; height: 1.5rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            💳 Payment Proof
        </h3>

        @if($record->renewal_payment_proof)
            @php
                $imagePath = storage_path('app/public/' . $record->renewal_payment_proof);
                $imageExists = file_exists($imagePath);
                $imageUrl = asset('storage/' . $record->renewal_payment_proof);
            @endphp

            @if($imageExists)
                {{-- Image Display --}}
                <div style="text-align: center;">
                    <a href="{{ $imageUrl }}" target="_blank" style="text-decoration: none; display: inline-block;">
                        <div style="position: relative; display: inline-block; transition: transform 0.3s ease;">
                            <img src="{{ $imageUrl }}" 
                                 alt="Payment Proof" 
                                 style="max-width: 100%; height: auto; max-height: 500px; border-radius: 0.75rem; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2); border: 4px solid white; display: block;"
                                 onmouseover="this.parentElement.style.transform='scale(1.02)'; this.parentElement.style.boxShadow='0 20px 35px -5px rgba(0, 0, 0, 0.3)';"
                                 onmouseout="this.parentElement.style.transform='scale(1)'; this.parentElement.style.boxShadow='none';"
                                 onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}'; this.nextElementSibling.style.display='block';">
                            <div style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(239, 68, 68, 0.9); color: white; padding: 1rem; border-radius: 0.5rem; font-size: 0.875rem; text-align: center; width: 80%;">
                                ⚠️ Failed to load image
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Action Buttons --}}
                <div style="display: flex; justify-content: center; gap: 1rem; margin-top: 1.5rem; flex-wrap: wrap;">
                    <a href="{{ $imageUrl }}" 
                       target="_blank"
                       style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: white; font-weight: 600; border-radius: 0.5rem; text-decoration: none; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); transition: all 0.3s ease;"
                       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.2)';"
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1)';">
                        <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                        </svg>
                        View Full Size
                    </a>
                    <a href="{{ $imageUrl }}" 
                       download="payment-proof-{{ $record->nok_id }}.jpg"
                       style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); color: white; font-weight: 600; border-radius: 0.5rem; text-decoration: none; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); transition: all 0.3s ease;"
                       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.2)';"
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1)';">
                        <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download Image
                    </a>
                </div>

                {{-- Info Tip --}}
                <div style="margin-top: 1rem; padding: 0.75rem 1rem; background-color: #dbeafe; border-left: 4px solid #3b82f6; border-radius: 0.5rem;">
                    <p style="font-size: 0.875rem; color: #1e40af; margin: 0;">
                        <strong>💡 Tip:</strong> Click "View Full Size" to open the image in a new tab, or use "Download Image" to save it.
                    </p>
                </div>
            @else
                {{-- File Not Found Error --}}
                <div style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); border: 2px solid #ef4444; border-radius: 0.75rem; padding: 1.5rem;">
                    <div style="display: flex; align-items: flex-start; gap: 1rem;">
                        <svg style="width: 3rem; height: 3rem; color: #dc2626; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div style="flex: 1;">
                            <h4 style="font-size: 1.125rem; font-weight: 700; color: #991b1b; margin: 0 0 0.75rem 0;">⚠️ Image File Not Found</h4>
                            <p style="font-size: 0.875rem; color: #7f1d1d; margin: 0 0 1rem 0;">The payment proof exists in the database but the file couldn't be found on the server.</p>
                            
                            <div style="background-color: white; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #f87171; margin-bottom: 0.75rem;">
                                <p style="font-size: 0.75rem; color: #6b7280; margin: 0 0 0.25rem 0;"><strong>Database Path:</strong></p>
                                <code style="font-size: 0.75rem; background-color: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 0.25rem; display: block; word-break: break-all; font-family: monospace;">{{ $record->renewal_payment_proof }}</code>
                            </div>
                            
                            <div style="background-color: white; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #f87171; margin-bottom: 0.75rem;">
                                <p style="font-size: 0.75rem; color: #6b7280; margin: 0 0 0.25rem 0;"><strong>Expected Location:</strong></p>
                                <code style="font-size: 0.75rem; background-color: #f3f4f6; padding: 0.25rem 0.5rem; border-radius: 0.25rem; display: block; word-break: break-all; font-family: monospace;">{{ $imagePath }}</code>
                            </div>
                            
                            <div style="background-color: #fef3c7; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #fbbf24;">
                                <p style="font-size: 0.875rem; color: #78350f; margin: 0; line-height: 1.6;">
                                    <strong>💡 Troubleshooting Steps:</strong><br>
                                    1. Verify the file was uploaded correctly<br>
                                    2. Run: <code style="background-color: #fde68a; padding: 0.125rem 0.25rem; border-radius: 0.25rem;">php artisan storage:link</code><br>
                                    3. Check file permissions on storage directory<br>
                                    4. Ensure the file exists at: <code style="background-color: #fde68a; padding: 0.125rem 0.25rem; border-radius: 0.25rem;">storage/app/public/...</code>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @else
            {{-- No Payment Proof Uploaded --}}
            <div style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); border: 2px dashed #9ca3af; border-radius: 0.75rem; padding: 3rem 1.5rem; text-align: center;">
                <svg style="width: 5rem; height: 5rem; margin: 0 auto 1rem auto; color: #9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h4 style="font-size: 1.125rem; font-weight: 600; color: #374151; margin: 0 0 0.5rem 0;">No Payment Proof Uploaded</h4>
                <p style="font-size: 0.875rem; color: #6b7280; margin: 0;">The member has not uploaded payment proof yet.</p>
            </div>
        @endif
    </div>

    {{-- Updated Member Details Section --}}
    <div style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border-radius: 1rem; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 2px solid #86efac;">
        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem; color: #065f46; display: flex; align-items: center;">
            <svg style="width: 1.5rem; height: 1.5rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            📝 Updated Member Details
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
            <div style="background-color: white; border-radius: 0.5rem; padding: 1rem; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); border: 1px solid #d1fae5;">
                <p style="font-size: 0.875rem; color: #6b7280; margin: 0 0 0.25rem 0; font-weight: 500;">👤 Full Name</p>
                <p style="font-weight: 600; color: #111827; margin: 0;">{{ $record->memberName ?? 'N/A' }}</p>
            </div>
            <div style="background-color: white; border-radius: 0.5rem; padding: 1rem; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); border: 1px solid #d1fae5;">
                <p style="font-size: 0.875rem; color: #6b7280; margin: 0 0 0.25rem 0; font-weight: 500;">📧 Email</p>
                <p style="font-weight: 600; color: #111827; margin: 0; word-break: break-all;">{{ $record->email ?? 'N/A' }}</p>
            </div>
            <div style="background-color: white; border-radius: 0.5rem; padding: 1rem; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); border: 1px solid #d1fae5;">
                <p style="font-size: 0.875rem; color: #6b7280; margin: 0 0 0.25rem 0; font-weight: 500;">📱 Mobile</p>
                <p style="font-weight: 600; color: #111827; margin: 0;">{{ $record->mobile ?? 'N/A' }}</p>
            </div>
            <div style="background-color: white; border-radius: 0.5rem; padding: 1rem; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); border: 1px solid #d1fae5;">
                <p style="font-size: 0.875rem; color: #6b7280; margin: 0 0 0.25rem 0; font-weight: 500;">💬 WhatsApp</p>
                <p style="font-weight: 600; color: #111827; margin: 0;">{{ $record->whatsapp ?? 'N/A' }}</p>
            </div>
            <div style="background-color: white; border-radius: 0.5rem; padding: 1rem; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); border: 1px solid #d1fae5; grid-column: 1 / -1;">
                <p style="font-size: 0.875rem; color: #6b7280; margin: 0 0 0.25rem 0; font-weight: 500;">🏠 Address</p>
                <p style="font-weight: 600; color: #111827; margin: 0;">{{ $record->address ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    {{-- Request Information Section --}}
    <div style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); border-radius: 1rem; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 2px solid #d1d5db;">
        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1rem; color: #374151; display: flex; align-items: center;">
            <svg style="width: 1.5rem; height: 1.5rem; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            📋 Request Information
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
            <div style="background-color: white; border-radius: 0.5rem; padding: 1rem; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
                <p style="font-size: 0.875rem; color: #6b7280; margin: 0 0 0.25rem 0; font-weight: 500;">🆔 NOK ID</p>
                <p style="font-weight: 700; color: #111827; margin: 0; font-size: 1.125rem;">{{ $record->nok_id ?? 'N/A' }}</p>
            </div>
            <div style="background-color: white; border-radius: 0.5rem; padding: 1rem; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
                <p style="font-size: 0.875rem; color: #6b7280; margin: 0 0 0.25rem 0; font-weight: 500;">🪪 Civil ID</p>
                <p style="font-weight: 700; color: #111827; margin: 0; font-size: 1.125rem;">{{ $record->civil_id ?? 'N/A' }}</p>
            </div>
            <div style="background-color: white; border-radius: 0.5rem; padding: 1rem; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
                <p style="font-size: 0.875rem; color: #6b7280; margin: 0 0 0.25rem 0; font-weight: 500;">📅 Requested On</p>
                <p style="font-weight: 600; color: #111827; margin: 0;">
                    {{ $record->renewal_requested_at ? (is_string($record->renewal_requested_at) ? \Carbon\Carbon::parse($record->renewal_requested_at)->format('d M Y, h:i A') : $record->renewal_requested_at->format('d M Y, h:i A')) : 'N/A' }}
                </p>
            </div>
            <div style="background-color: white; border-radius: 0.5rem; padding: 1rem; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
                <p style="font-size: 0.875rem; color: #6b7280; margin: 0 0 0.5rem 0; font-weight: 500;">📊 Status</p>
                <span style="display: inline-flex; align-items: center; padding: 0.375rem 0.875rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 600; {{ $record->renewal_status === 'pending' ? 'background-color: #fef3c7; color: #92400e; border: 2px solid #fbbf24;' : 'background-color: #d1fae5; color: #065f46; border: 2px solid #34d399;' }}">
                    @if($record->renewal_status === 'pending')
                        ⏳ {{ ucfirst($record->renewal_status) }}
                    @else
                        ✅ {{ ucfirst($record->renewal_status) }}
                    @endif
                </span>
            </div>
            <div style="background-color: white; border-radius: 0.5rem; padding: 1rem; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb; grid-column: 1 / -1;">
                <p style="font-size: 0.875rem; color: #6b7280; margin: 0 0 0.25rem 0; font-weight: 500;">⏰ Current Card Expiry</p>
                <p style="font-weight: 700; color: #111827; margin: 0; font-size: 1.125rem;">
                    {{ $record->card_valid_until ? (is_string($record->card_valid_until) ? \Carbon\Carbon::parse($record->card_valid_until)->format('d M Y') : $record->card_valid_until->format('d M Y')) : 'N/A' }}
                </p>
            </div>
        </div>
    </div>
</div>

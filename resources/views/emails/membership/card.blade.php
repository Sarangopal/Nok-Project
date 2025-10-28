<x-mail::message>

{{-- =========================
     HEADER SECTION
========================= --}}
<div style="background:linear-gradient(135deg, #004aad, #0078d7); padding:35px; border-radius:18px; text-align:center; color:white; margin-bottom:30px;">
    <img src="{{ $message->embed(public_path('img/NOK-Card-BG.jpg')) }}" alt="NOK Logo" style="width:90px; height:auto; margin-bottom:12px;">
    <h1 style="margin:0; font-size:28px; font-weight:700;">Nightingales of Kuwait</h1>
    <p style="font-size:15px; margin-top:8px; opacity:0.9;">Empowering Healthcare Professionals 🌍</p>
</div>

@php
    $isNewApproved = $record->login_status === 'approved' && !$record->last_renewed_at;
    $isRenewalApproved = $record->renewal_status === 'approved' && $record->last_renewed_at;
    $isRejected = in_array($record->login_status, ['rejected']) || in_array($record->renewal_status, ['rejected']);
    $isPending = in_array($record->login_status, ['pending']) || in_array($record->renewal_status, ['pending']);
@endphp


{{-- =========================
     STATUS MESSAGE SECTION
========================= --}}
@if($isNewApproved)
# 🎉 Congratulations, {{ $record->memberName }}!

Your **membership registration** has been successfully **approved**.  
Welcome aboard to the **Nightingales of Kuwait** family! 💙

@elseif($isRenewalApproved)
# 🔁 Congratulations, {{ $record->memberName }}!

Your **membership renewal** has been successfully **approved**.  
Thank you for continuing your journey with us! 🌟

@elseif($isRejected)
# ❌ Hello, {{ $record->memberName }}

We’re sorry to inform you that your membership request has been **rejected**.  
If you believe this is a mistake, please contact our support team.

@elseif($isPending)
# ⏳ Hello, {{ $record->memberName }}

Your membership status is currently **pending**.  
Our team will review your application and notify you once it's processed. 🕓
@endif


---

{{-- =========================
     MEMBERSHIP DETAILS CARD
========================= --}}
<x-mail::panel>
<div style="background:#ffffff; border-radius:14px; box-shadow:0 3px 10px rgba(0,0,0,0.08); padding:25px; margin-bottom:30px;">
    <h3 style="text-align:center; color:#004aad; margin-bottom:15px;">📋 Membership Summary</h3>
    <table style="width:100%; border-collapse:collapse; font-size:15px; color:#333;">
        <tbody>
            <tr>
                <td style="padding:8px 0;"><strong>🆔 NOK ID</strong></td>
                <td style="text-align:right;">{{ $record->nok_id ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding:8px 0;"><strong>👤 Name</strong></td>
                <td style="text-align:right;">{{ $record->memberName }}</td>
            </tr>
            <tr>
                <td style="padding:8px 0;"><strong>📅 Date of Joining</strong></td>
                <td style="text-align:right;">{{ $record->card_issued_at?->format('d-m-Y') ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding:8px 0;"><strong>📅 Expiry Date</strong></td>
                <td style="text-align:right;">{{ $record->card_valid_until?->format('d-m-Y') ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding:8px 0;"><strong>🔖 Status</strong></td>
                <td style="text-align:right; text-transform:capitalize;">
                    {{-- Show login status if registration, renewal_status if renewal --}}
                    {{ $record->last_renewed_at ? ($record->renewal_status ?? 'pending') : ($record->login_status ?? 'pending') }}
                </td>
            </tr>
        </tbody>
    </table>
</div>
</x-mail::panel>


{{-- =========================
     LOGIN CREDENTIALS (FOR NEW APPROVAL)
========================= --}}
@if(!empty($password) && $isNewApproved)
<x-mail::panel>
### 🔐 Member Login Credentials

You can now log in to the member portal at:  
👉 [{{ url('/member/login') }}]({{ url('/member/login') }})

- **📧 Email:** {{ $record->email }}  
- **🆔 Civil ID:** {{ $record->civil_id ?? 'N/A' }}  
- **🔑 Password:** `{{ $password }}`  

Please keep this information secure and **change your password** after your first login.
</x-mail::panel>
@endif


{{-- =========================
     DOWNLOAD BUTTON (FOR APPROVED MEMBERS)
========================= --}}
@if($isNewApproved || $isRenewalApproved)
<div style="text-align:center; margin-bottom:25px;">
    <x-mail::button :url="route('membership.card.download', $record->id)" color="success">
        🎫 Download Your Membership Card
    </x-mail::button>
</div>
@endif


{{-- =========================
     FOOTER SECTION
========================= --}}
<div style="text-align:center; margin-top:35px; padding-top:20px; border-top:1px solid #ddd;">
    <p style="font-size:15px; color:#444; margin-bottom:6px;">
        Thank you for being a part of <strong>Nightingales of Kuwait</strong> 💙  
    </p>
    <p style="font-size:13px; color:#777; line-height:1.6;">
        Follow us on social media to stay updated with our latest activities.<br>
        📧 <a href="mailto:info@nightingaleskuwait.org" style="color:#0078d7; text-decoration:none;">info@nightingaleskuwait.org</a>
    </p>
    <p style="font-size:12px; color:#999; margin-top:10px;">
        This is an automated message — please do not reply.
    </p>
</div>

</x-mail::message>

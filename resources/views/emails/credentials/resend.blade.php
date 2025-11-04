<x-mail::message>

{{-- =========================
     HEADER SECTION
========================= --}}
<div style="background:linear-gradient(135deg, #d32f2f, #f44336); padding:35px; border-radius:18px; text-align:center; color:white; margin-bottom:30px;">
    <div style="font-size: 48px; margin-bottom: 12px;">🔐</div>
    <h1 style="margin:0; font-size:28px; font-weight:700;">Password Reset</h1>
    <p style="font-size:15px; margin-top:8px; opacity:0.9;">Your Login Credentials Have Been Updated</p>
</div>

# Hello, {{ $record->memberName }}

Your login password has been **reset by an administrator**. Your old password will no longer work.

Please use the new credentials below to access your member portal.

---

{{-- =========================
     NEW CREDENTIALS SECTION
========================= --}}
<x-mail::panel>
## 🔑 Your New Login Credentials

<div style="background:#f5f5f5; padding:20px; border-radius:10px; border-left:4px solid #d32f2f;">
    <table style="width:100%; font-size:15px;">
        <tr>
            <td style="padding:8px 0;"><strong>📧 Email:</strong></td>
            <td style="text-align:right; font-family:monospace;">{{ $record->email }}</td>
        </tr>
        <tr>
            <td style="padding:8px 0;"><strong>🆔 Civil ID:</strong></td>
            <td style="text-align:right; font-family:monospace;">{{ $record->civil_id }}</td>
        </tr>
        <tr>
            <td style="padding:8px 0;"><strong>🔑 New Password:</strong></td>
            <td style="text-align:right; font-family:monospace; background:#fff; padding:8px; border-radius:5px; font-weight:bold; color:#d32f2f;">{{ $password }}</td>
        </tr>
    </table>
</div>

### ⚠️ Important Security Notice

- **Your old password no longer works**
- Please **change your password** immediately after logging in
- Keep this password **secure and confidential**
- Do not share your credentials with anyone
</x-mail::panel>

---

{{-- =========================
     LOGIN BUTTON
========================= --}}
<div style="text-align:center; margin:30px 0;">
<x-mail::button :url="$loginUrl" color="error">
🔐 Login to Member Portal
</x-mail::button>
</div>

---

{{-- =========================
     MEMBER INFO
========================= --}}
<x-mail::panel>
### 👤 Account Information

- **NOK ID:** {{ $record->nok_id ?? 'N/A' }}
- **Member Name:** {{ $record->memberName }}
- **Email:** {{ $record->email }}
- **Civil ID:** {{ $record->civil_id }}
</x-mail::panel>

---

{{-- =========================
     FOOTER SECTION
========================= --}}
<div style="text-align:center; margin-top:35px; padding-top:20px; border-top:1px solid #ddd;">
    <p style="font-size:15px; color:#444; margin-bottom:6px;">
        <strong>Nightingales of Kuwait</strong> 💙
    </p>
    <p style="font-size:13px; color:#777; line-height:1.6;">
        If you did not request this password reset, please contact our support team immediately.<br>
        📧 <a href="mailto:info@nightingaleskuwait.org" style="color:#d32f2f; text-decoration:none;">info@nightingaleskuwait.org</a>
    </p>
    <p style="font-size:12px; color:#999; margin-top:10px;">
        This is an automated security notification — please do not reply to this email.
    </p>
</div>

</x-mail::message>


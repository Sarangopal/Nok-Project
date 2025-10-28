<x-mail::message>
# ✅ Renewal Request Submitted Successfully

Dear **{{ $member->memberName }}**,

Thank you for submitting your membership renewal request! We have received your application and payment proof.

---

<x-mail::panel>
### 📋 Request Details

- **NOK ID:** {{ $member->nok_id ?? 'N/A' }}
- **Civil ID:** {{ $member->civil_id ?? 'N/A' }}
- **Submitted On:** {{ $member->renewal_requested_at?->format('d M Y, h:i A') ?? 'Just now' }}
- **Current Expiry:** {{ $member->card_valid_until?->format('d M Y') ?? 'N/A' }}
- **Status:** 🟡 Pending Admin Approval
</x-mail::panel>

---

### 🔍 What Happens Next?

1. **Review Process:** Our admin team will review your renewal request and payment proof
2. **Verification:** We'll verify your payment and membership details
3. **Approval:** Once approved, your membership will be extended
4. **Notification:** You'll receive an email confirmation when approved

**Expected Processing Time:** 2-3 business days

---

### 📧 Stay Updated

You can check the status of your renewal request anytime by logging into your member portal:

<x-mail::button color="primary" :url="url('/member/panel')">
🔐 View Request Status
</x-mail::button>

---

### ❓ Need Help?

If you have any questions about your renewal request, please feel free to contact us:

- **Email:** nightingalesofkuwait24@gmail.com
- **Phone:** +965 6653 4053

---

Thank you for your continued membership with NOK!

Best regards,  
**💙 Nightingales of Kuwait Team**
</x-mail::message>


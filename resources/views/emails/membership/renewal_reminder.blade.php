<x-mail::message>
# 🔔 Membership Renewal Reminder

Dear **{{ $memberName }}**,

This is a friendly reminder that your NOK membership card will expire soon.

---

<x-mail::panel>
### ⚠️ Expiry Information

- **Valid Until:** {{ $validUntil }}
@if($daysLeft > 0)
- **Days Remaining:** {{ $daysLeft }} days
@else
- **Status:** ⚠️ Your membership has expired today
@endif
</x-mail::panel>

---

### 📝 How to Renew

To continue enjoying your membership benefits, please renew your membership:

1. Login to the member portal
2. Navigate to your dashboard
3. Click on "Request Renewal"
4. Upload your payment proof
5. Submit your renewal request

<x-mail::button color="primary" :url="url('/member/panel/login')">
🔐 Login to Member Portal
</x-mail::button>

---

### 💡 Benefits of Renewing


✅ Participation in NOK events  
✅ Networking opportunities  
✅ Professional development resources  
✅ Community support and engagement  

---

If you have any questions or need assistance, please contact us.

Thank you for being a valued member,  
**💙 Nightingales of Kuwait**
</x-mail::message>




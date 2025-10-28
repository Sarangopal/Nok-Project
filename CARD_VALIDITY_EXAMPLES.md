# Membership Card Validity System - Examples

## 🎯 Core Principle
**All membership cards follow CALENDAR YEAR validity (Jan 1 - Dec 31)**

All members, regardless of when they join, must renew annually by **December 31st**.

---

## 📅 Example Scenarios

### Scenario 1: New Registration in January
```
Registration Date: January 15, 2025
Approval Date: January 20, 2025
Card Issued: January 20, 2025
Card Valid Until: December 31, 2025

✅ Member gets almost full year (345 days)
```

**Timeline:**
```
Jan 20, 2025 ────────────────────────────► Dec 31, 2025
   ↑ Card Issued                              ↑ Card Expires
   (11 months, 11 days validity)
```

---

### Scenario 2: New Registration in October
```
Registration Date: October 15, 2025
Approval Date: October 21, 2025
Card Issued: October 21, 2025
Card Valid Until: December 31, 2025

⚠️ Member gets only 2.5 months (71 days)
⚠️ Must renew before Dec 31 to continue membership in 2026
```

**Timeline:**
```
Oct 21, 2025 ─────────────► Dec 31, 2025
   ↑ Card Issued            ↑ Card Expires
   (2 months, 10 days)
```

---

### Scenario 3: Renewal Before Expiry (Early Renewal)
```
Original Card Issued: January 15, 2025
Original Card Expires: December 31, 2025

Renewal Requested: November 15, 2025 (card still valid)
Renewal Approved: November 20, 2025
New Card Valid Until: December 31, 2025

⚠️ Card still expires Dec 31, 2025 (not extended to 2026)
⚠️ Member must wait until 2026 to renew for next year
```

**Timeline:**
```
Jan 15, 2025          Nov 20, 2025         Dec 31, 2025
   ↑                      ↑                     ↑
   Card Issued         Renewed Early        Still Expires
                     (same year end)
```

---

### Scenario 4: Renewal After Expiry (Late Renewal in New Year)
```
Original Card Issued: January 15, 2025
Original Card Expired: December 31, 2025

Membership Expired: January 1, 2026
Renewal Requested: February 10, 2026 (after expiry)
Renewal Approved: February 15, 2026
New Card Valid Until: December 31, 2026

✅ Renewed for 2026, expires end of 2026
```

**Timeline:**
```
2025                                    2026
Jan 15 ────────► Dec 31 ───(expired)───► Feb 15 ────────► Dec 31
   ↑               ↑                        ↑               ↑
 Issued         Expired                  Renewed         Expires
                                      (for year 2026)
```

---

### Scenario 5: Multiple Years Example
```
Year 2025:
  - Joined: March 10, 2025
  - Approved: March 15, 2025
  - Card Valid Until: December 31, 2025
  - Status: Active (9.5 months validity)

Year 2026:
  - Renewal Requested: November 25, 2025
  - Renewal Approved: December 1, 2025
  - Card Valid Until: December 31, 2025 (NOT 2026!)
  - Status: Still expires Dec 31, 2025
  
  - Second Renewal Requested: January 5, 2026
  - Second Renewal Approved: January 10, 2026
  - Card Valid Until: December 31, 2026
  - Status: Active for 2026

Year 2027:
  - Renewal Requested: November 20, 2026
  - Renewal Approved: November 25, 2026
  - Card Valid Until: December 31, 2026 (NOT 2027!)
  - Must renew again in 2027 for 2027 validity
```

---

## 🔔 Automatic Renewal Reminder System

### Email Reminder Schedule
Members receive automatic reminder emails at:

| Days Before Expiry | Example Date (for Dec 31 expiry) | Subject |
|-------------------|----------------------------------|---------|
| 30 days | December 1 | "Renewal Reminder - 30 days left" |
| 15 days | December 16 | "Renewal Reminder - 15 days left" |
| 7 days | December 24 | "Urgent: Renewal Reminder - 7 days left" |
| 1 day | December 30 | "Final Reminder - Card expires tomorrow!" |
| 0 days (expiry day) | December 31 | "Your membership expired today" |

### Reminder Behavior
- Runs **daily at 8:00 AM** (Kuwait time)
- Sends to all approved members approaching expiry
- Prevents duplicate emails using tracking system
- Continues sending reminders even after expiry (0 days reminder)

---

## 📊 Calendar Year vs Rolling Year Comparison

### ❌ OLD System (Rolling Year - INCORRECT)
```
Member A joins Jan 15, 2025 → Expires Jan 14, 2026
Member B joins Oct 21, 2025 → Expires Oct 20, 2026
Member C joins Dec 1, 2025 → Expires Nov 30, 2026

Problem: Different expiry dates, complex tracking
```

### ✅ NEW System (Calendar Year - CORRECT)
```
Member A joins Jan 15, 2025 → Expires Dec 31, 2025
Member B joins Oct 21, 2025 → Expires Dec 31, 2025
Member C joins Dec 1, 2025 → Expires Dec 31, 2025

Benefit: ALL members expire same date, easy management
```

---

## 💡 Important Notes

### For Members
1. **Join early in the year** to get maximum value (full 12 months)
2. **Join late in the year** means shorter validity (but same annual renewal)
3. **Must renew before Dec 31** to maintain continuous membership
4. **Late renewals in new year** are valid for that calendar year only

### For Admins
1. All members expire on **December 31** regardless of join date
2. Renewal approvals always set validity to **end of current year**
3. No pro-rata or partial year memberships
4. Simplified annual renewal process (one date for everyone)

### For System
1. `card_valid_until` is always set to `December 31` of current year
2. Uses `computeCalendarYearValidity()` method
3. Formula: `Carbon::parse($date)->endOfYear()`
4. Automatic reminders configured in `routes/console.php`

---

## 🔧 Technical Implementation

### When Card Validity is Set

#### New Registration Approval
```php
// In RegistrationsTable.php (Approve action)
$record->login_status = 'approved';
$record->card_issued_at = now();
$record->card_valid_until = $record->computeCalendarYearValidity();
// Result: December 31 of current year
```

#### Renewal Approval
```php
// In RenewalRequestsTable.php (Approve action)
$record->renewal_status = 'approved';
$record->last_renewed_at = now();
$record->card_valid_until = $record->computeCalendarYearValidity();
// Result: December 31 of current year
```

#### Auto-set on Save (Model Event)
```php
// In Registration.php (booted method)
if ($isLoginApproved && !$registration->card_valid_until && $registration->renewal_count == 0) {
    $registration->card_valid_until = $registration->computeCalendarYearValidity();
}
```

---

## 🎯 Real-World Example: Member Journey

### John Doe - Complete Membership Lifecycle

**2025:**
- **Mar 15, 2025**: Joins NOK Kuwait, submits registration
- **Mar 18, 2025**: Admin approves registration
  - Card issued: March 18, 2025
  - Card expires: **December 31, 2025**
  - Validity: 9 months, 13 days
- **Dec 1, 2025**: Receives email "30 days before expiry"
- **Dec 16, 2025**: Receives email "15 days before expiry"
- **Dec 20, 2025**: John submits renewal request with payment
- **Dec 22, 2025**: Admin approves renewal
  - Card expires: **December 31, 2025** (no change - same year)
  - ⚠️ John must renew again in 2026!

**2026:**
- **Jan 1, 2026**: Card from 2025 expires
- **Jan 5, 2026**: John submits new renewal request for 2026
- **Jan 7, 2026**: Admin approves renewal
  - Card expires: **December 31, 2026**
  - Validity: Full year 2026 (358 days)
- **Dec 1, 2026**: Receives email "30 days before expiry"
- **Dec 15, 2026**: John submits renewal for 2027
- **Dec 18, 2026**: Admin approves
  - Card expires: **December 31, 2026** (no change - same year)

**2027:**
- **Jan 3, 2027**: John submits renewal for 2027
- **Jan 5, 2027**: Admin approves
  - Card expires: **December 31, 2027**
- And the cycle continues...

---

## 📞 Support

For questions about membership validity:
- **Email**: admin@nokkuwait.org
- **Phone**: +965-XXXX-XXXX
- **Admin Panel**: http://127.0.0.1:8000/admin

---

## 🧪 Testing

Run unit tests to verify calendar year validity:
```bash
php artisan test tests/Unit/CalendarYearValidityTest.php
```

Test renewal reminder system:
```bash
php artisan members:send-renewal-reminders
```

---

**Last Updated**: October 28, 2025  
**System Version**: 2.0 (Calendar Year Validity)


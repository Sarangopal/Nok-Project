# ✅ Renewal Status Logic - FIXED

## 🔍 **Problem Identified:**

The "Renewal Due" column was showing **"Renewed" (green)** for expired/expiring cards, which was incorrect.

---

## ✅ **Fixed Logic:**

### **New "Renewal Status" Column Shows:**

1. **🔴 "Renewal Due" (Red)**
   - Card is expired OR expiring within 30 days
   - Member has NOT submitted renewal request yet
   - Action needed: Member needs to submit renewal request

2. **🟡 "Renewal Pending" (Yellow/Orange)**
   - Card is expired OR expiring within 30 days
   - Member HAS submitted renewal request
   - `renewal_status = 'pending'` AND `renewal_requested_at` is set
   - Action needed: Admin needs to approve

3. **🟢 "Renewed" (Green)**
   - Renewal approved by admin (`renewal_status = 'approved'`)
   - OR card is still valid (not expired/expiring within 30 days)
   - Everything is good!

---

## 📊 **Workflow Example:**

### **Scenario 1: Card Expiring in 5 Days (No Request Yet)**
```
Expiry Date: "Expiring Soon (5 days)" [Yellow]
Renewal Status: "Renewal Due" [Red] ← NEEDS ACTION
Renewal Status Column: "pending"
```
**What happens:**
- Member sees "Request Renewal" button in member panel
- Member submits renewal request

### **Scenario 2: Card Expiring, Request Submitted**
```
Expiry Date: "Expiring Soon (5 days)" [Yellow]
Renewal Status: "Renewal Pending" [Yellow] ← WAITING FOR ADMIN
Renewal Status Column: "pending"
```
**What happens:**
- Admin sees "Approve" button
- Admin approves the renewal

### **Scenario 3: Renewal Approved**
```
Expiry Date: "Valid" [Green]
Renewal Status: "Renewed" [Green] ← ALL GOOD
Renewal Status Column: "approved"
```
**What happens:**
- Member gets new card with extended validity
- Card valid until end of current year

---

## 🎯 **Code Changes:**

**File:** `app/Filament/Resources/Renewals/Tables/RenewalsTable.php`

**Before (Lines 52-63):**
```php
BadgeColumn::make('is_renewal_due')
    ->label('Renewal Due')
    ->colors([
        'success' => fn($state) => $state === 'Renewed',
        'danger'  => fn($state) => $state === 'Renewal Due',
    ])
    ->getStateUsing(fn($record) => $record->last_renewed_at || (!$record->is_renewal_due && $record->renewal_status === 'approved')
        ? 'Renewed' 
        : 'Renewal Due'
    ),
```

**After (Lines 52-80):**
```php
BadgeColumn::make('is_renewal_due')
    ->label('Renewal Status')
    ->colors([
        'success' => fn($state) => $state === 'Renewed',           // green
        'warning' => fn($state) => $state === 'Renewal Pending',   // yellow/orange
        'danger'  => fn($state) => $state === 'Renewal Due',       // red
    ])
    ->getStateUsing(function($record) {
        // Check if card is expired or expiring soon
        $isExpiredOrExpiring = false;
        if ($record->card_valid_until) {
            $expiryDate = Carbon::parse($record->card_valid_until);
            $daysUntilExpiry = now()->diffInDays($expiryDate, false);
            $isExpiredOrExpiring = $daysUntilExpiry <= 30; // expired or expiring within 30 days
        }
        
        // If card is expired/expiring AND renewal request submitted (pending)
        if ($isExpiredOrExpiring && $record->renewal_status === 'pending' && $record->renewal_requested_at) {
            return 'Renewal Pending';
        }
        
        // If card is expired/expiring AND no renewal request yet
        if ($isExpiredOrExpiring && $record->renewal_status !== 'approved') {
            return 'Renewal Due';
        }
        
        // If renewal approved or card is still valid
        return 'Renewed';
    }),
```

---

## ✅ **What to Test:**

1. **Refresh the admin Renewals page** (`http://127.0.0.1:8000/admin/renewals`)

2. **Check these scenarios:**
   - Members with expired cards (0 days) → Should show **"Renewal Due" (Red)**
   - Members with expiring cards (5, 13, 18, 28 days) → Should show **"Renewal Due" (Red)**
   - Members who submitted renewal request → Should show **"Renewal Pending" (Yellow)**
   - Members with approved renewals → Should show **"Renewed" (Green)**

---

## 🎯 **Summary:**

✅ **Fixed:** "Renewal Status" column now correctly reflects the actual state
✅ **Added:** Three states instead of two (Renewal Due, Renewal Pending, Renewed)
✅ **Logic:** Based on card expiry date AND renewal request status
✅ **Ready:** Refresh the page to see the corrected statuses!


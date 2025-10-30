# Renewal Date Extension Fix

## Problem
When admin approved a renewal request, the member's `card_valid_until` date was NOT being extended. It stayed at the same date (e.g., Dec 31, 2025).

### Example:
- Member has card expiring: **Dec 31, 2025**
- Admin approves renewal
- Expected: **Dec 31, 2026** (extended by 1 year)
- Actual (BEFORE FIX): **Dec 31, 2025** (no change) ❌

## Root Cause
The `computeCalendarYearValidity()` function was calculating the end of the **current year** instead of extending by 1 year for renewals.

### Old Logic (app/Models/Registration.php):
```php
public function computeCalendarYearValidity(?Carbon $baseDate = null): Carbon
{
    $date = $baseDate ?: ($this->last_renewed_at ?: ($this->card_issued_at ?: now()));
    return Carbon::parse($date)->endOfYear();  // ❌ Always returns current year end
}
```

## Solution
Added an `$isRenewal` parameter to properly extend the validity by 1 year for renewals.

### New Logic (app/Models/Registration.php):
```php
public function computeCalendarYearValidity(?Carbon $baseDate = null, bool $isRenewal = false): Carbon
{
    if ($isRenewal && $this->card_valid_until) {
        // For renewals, extend from current expiry date by 1 year
        return Carbon::parse($this->card_valid_until)->addYear()->endOfYear();
    }
    
    // For new registrations, set to end of current year
    $date = $baseDate ?: ($this->last_renewed_at ?: ($this->card_issued_at ?: now()));
    return Carbon::parse($date)->endOfYear();
}
```

### Updated Renewal Approval (app/Filament/Resources/RenewalRequests/Tables/RenewalRequestsTable.php):
```php
// Pass isRenewal = true when approving renewals
$record->card_valid_until = $record->computeCalendarYearValidity(null, true);
```

## Results

### Before Fix:
| Current Expiry | After Approval | Result |
|----------------|----------------|--------|
| Dec 31, 2025   | Dec 31, 2025   | ❌ No extension |

### After Fix:
| Current Expiry | After Approval | Result |
|----------------|----------------|--------|
| Dec 31, 2025   | Dec 31, 2026   | ✅ Extended by 1 year |
| Dec 31, 2024   | Dec 31, 2025   | ✅ Extended by 1 year |

## Files Modified
1. ✅ `app/Models/Registration.php` - Added `$isRenewal` parameter
2. ✅ `app/Filament/Resources/RenewalRequests/Tables/RenewalRequestsTable.php` - Pass `true` for renewals
3. ✅ Updated modal description to show current and new expiry dates

## Testing
To test the fix:
1. Go to Admin Panel → Renewal Requests
2. Find a pending renewal request
3. Click "Approve Renewal"
4. The modal will show: "Current expiry: Dec 31, 2025. New expiry will be: Dec 31, 2026 (extended by 1 year)."
5. After approval, check the member's profile - renewal date should be Dec 31, 2026

## Impact
- ✅ Renewals now properly extend membership by 1 full year
- ✅ Members who renew early still get full year extension
- ✅ Expired memberships get extended to end of next year when approved
- ✅ New registrations still get set to end of current year (unchanged)



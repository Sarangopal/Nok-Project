<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_type','nok_id','doj','memberName','age','gender','email','mobile','whatsapp',
        'department','job_title','institution','passport','civil_id','blood_group',
        'address','phone_india','nominee_name','nominee_relation','nominee_contact',
        'guardian_name','guardian_contact','bank_account_name','account_number','ifsc_code','bank_branch',
        'renewal_status','login_status','card_issued_at', 'last_renewed_at', 'renewal_count','card_valid_until',
        'password','renewal_requested_at','renewal_payment_proof',
    ];

    protected $casts = [
        'doj' => 'date', // Casting date column
        'card_issued_at' => 'datetime',
        'last_renewed_at' => 'datetime',
        'card_valid_until' => 'datetime',
        'card_issued_at' => 'datetime',
    ];
    // Accessor to check if renewal is due (1 year after Date of Joining)
    public function getIsRenewalDueAttribute(): bool
    {
        if (!$this->doj) {
            return false; // No date, so renewal is not due     
        }
        return now()->gte($this->doj->addYear()) && $this->renewal_status === 'pending';

    }

    protected static function booted(): void
    {
        static::saving(function (Registration $registration) {
            // Set card validity when first approved (new registration only)
            // For renewals, expiry is handled in the approval action
            $isLoginApproved = $registration->login_status === 'approved';
            
            // Only set expiry for NEW registrations (not renewals)
            if ($isLoginApproved && !$registration->card_valid_until && $registration->renewal_count == 0) {
                // Set to end of current calendar year (Dec 31)
                // This ensures all members must renew by year-end (Jan-Dec validity)
                $registration->card_valid_until = $registration->computeCalendarYearValidity();
            }
        });
    }

    public function computeCalendarYearValidity(?Carbon $baseDate = null, bool $isRenewal = false): Carbon
    {
        if ($isRenewal && $this->card_valid_until) {
            // For renewals, extend to December 31 of the NEXT year
            // Example: Expires Dec 31, 2024 → Renewed → New expiry: Dec 31, 2025
            // Example: Expires Dec 31, 2025 → Renewed → New expiry: Dec 31, 2026
            $currentExpiry = Carbon::parse($this->card_valid_until);
            return $currentExpiry->addYear()->endOfYear();
        }
        
        // For new registrations, set to December 31 of the current calendar year
        // This ensures all members get validity until Dec 31 of the current year
        return now()->endOfYear();
    }

    public function offers(): BelongsToMany
    {
        return $this->belongsToMany(Offer::class, 'member_offer', 'registration_id', 'offer_id')
            ->withTimestamps();
    }
}


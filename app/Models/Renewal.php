<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Renewal extends Model
{
    use HasFactory;

    protected $table = 'registrations'; // Existing table name

    protected $fillable = [
        'member_type','nok_id','doj','memberName','age','gender','email','mobile','whatsapp',
        'department','job_title','institution','passport','civil_id','blood_group',
        'address','phone_india','nominee_name','nominee_relation','nominee_contact',
        'guardian_name','guardian_contact','bank_account_name','account_number','ifsc_code','bank_branch',
        'renewal_status',     // pending, approved, rejected
        'card_issued_at',     // when the first card was issued
        'card_valid_until',   // expiry date (usually +1 year)
        'last_renewed_at',    // when last renewal happened
        'renewal_count',      // number of times renewed
    ];

    protected $casts = [
        'doj' => 'date', // Casting date column
        'card_issued_at' => 'datetime',
        'card_valid_until' => 'date',
        'last_renewed_at'  => 'datetime',
    ];



    // Check if expired
    public function getIsExpiredAttribute(): bool
    {
        return $this->card_valid_until && now()->gt($this->card_valid_until);
    }


    // Check if upcoming expiry (within 30 days)
    public function getIsExpiringSoonAttribute(): bool
    {
        return $this->card_valid_until && 
            $this->card_valid_until->isBetween(now(), now()->addDays(30));
    }

    // Renewal is due if expired or expiring soon
    public function getIsRenewalDueAttribute(): bool
    {
        return $this->is_expired || $this->is_expiring_soon;
    }

    /**
     * (Optional) Default expiry if no card issued
     */
    public function getDefaultExpiryAttribute($value) {
         return $value 
        ? Carbon::parse($value)   // use DB value if set
        : ($this->doj ? Carbon::parse($this->doj)->addYear() : null);

        // return $this->doj ? Carbon::parse($this->doj)->addYear() : null;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereDate('card_valid_until', '<=', now())          // expired
            ->orWhereDate('card_valid_until', '<=', now()->addDays(30)); // expiring soon
    }


    // // Check if renewal is due (1 year after Date of Joining)
    // public function getIsRenewalDueAttribute(): bool
    // {
    //     if (!$this->doj) {
    //         return false; // No date, so renewal is not due
    //     }
        
    //     return now()->gte($this->doj->addYear()) && $this->renewal_status === 'pending';
    // }

}

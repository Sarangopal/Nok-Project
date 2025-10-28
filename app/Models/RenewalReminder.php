<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RenewalReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id',
        'member_name',
        'email',
        'card_valid_until',
        'days_before_expiry',
        'status',
        'error_message',
    ];

    protected $casts = [
        'card_valid_until' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the registration that owns the renewal reminder.
     */
    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }
}





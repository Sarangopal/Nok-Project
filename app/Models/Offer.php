<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Offer extends Model
{
    protected $fillable = [
        'title', 'body', 'promo_code', 'starts_at', 'ends_at', 'active',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
        'active' => 'boolean',
    ];

    public function registrations(): BelongsToMany
    {
        return $this->belongsToMany(Registration::class, 'member_offer', 'offer_id', 'registration_id')
            ->withTimestamps();
    }

    public function members(): BelongsToMany
    {
        // Alias for readability when using Member guard model
        return $this->belongsToMany(Member::class, 'member_offer', 'offer_id', 'registration_id')
            ->withTimestamps();
    }
}



<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'civil_id',
        'ip_address',
        'user_agent',
        'was_successful',
        'verified_until',
    ];

    protected $casts = [
        'was_successful' => 'boolean',
        'verified_until' => 'datetime',
    ];
}




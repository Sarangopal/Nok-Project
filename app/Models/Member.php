<?php

namespace App\Models;

use App\Notifications\MemberResetPasswordNotification;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Contracts\Auth\CanResetPassword;

class Member extends Authenticatable implements FilamentUser, HasName, CanResetPassword
{
    use Notifiable;

    protected $table = 'registrations';

    protected $fillable = [
        'member_type','nok_id','doj','memberName','age','gender','email','password','mobile','whatsapp',
        'department','job_title','institution','passport','civil_id','blood_group','address','phone_india',
        'nominee_name','nominee_relation','nominee_contact','guardian_name','guardian_contact',
        'bank_account_name','account_number','ifsc_code','bank_branch','renewal_status','login_status','card_issued_at',
        'last_renewed_at','renewal_count','card_valid_until','renewal_date','photo_path','qr_code_path',
        'renewal_payment_proof','renewal_requested_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'doj' => 'date',
        'card_issued_at' => 'datetime',
        'last_renewed_at' => 'datetime',
        'card_valid_until' => 'datetime',
        'renewal_date' => 'date',
    ];

    public function offers(): BelongsToMany
    {
        return $this->belongsToMany(Offer::class, 'member_offer', 'registration_id', 'offer_id')
            ->withTimestamps();
    }

    // Auto-hash passwords when set
    public function setPasswordAttribute($value)
    {
        // Only hash if not already hashed
        if (!empty($value) && !str_starts_with($value, '$2y$')) {
            $this->attributes['password'] = bcrypt($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }

    // Filament User Interface Implementation
    public function canAccessPanel(Panel $panel): bool
    {
        // Allow access to member panel if approved (either login_status OR renewal_status)
        // This ensures both new members and renewed members can access the panel
        if ($panel->getId() === 'member') {
            return $this->login_status === 'approved' || $this->renewal_status === 'approved';
        }
        
        return false;
    }

    // Get the member's name for Filament
    public function getFilamentName(): string
    {
        return $this->memberName ?? $this->email ?? 'Member';
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MemberResetPasswordNotification($token));
    }
}



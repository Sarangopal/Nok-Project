<?php

namespace App\Http\Livewire;

use App\Models\Registration;
use App\Models\VerificationAttempt;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class PublicVerification extends Component implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public string $civilId = '';

    public ?string $resultMessage = null;

    public ?string $validUntil = null;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('civilId')
                    ->label('Civil ID')
                    ->required()
                    ->maxLength(20)
                    ->regex('/^[0-9A-Za-z\-]+$/')
                    ->autocomplete('off')
                    ->extraAttributes(['inputmode' => 'numeric'])
                    ->helperText('Enter your Civil ID to check membership verification.')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('website')
                    ->label('')
                    ->hiddenLabel()
                    ->extraInputAttributes(['autocomplete' => 'off'])
                    ->extraAttributes(['style' => 'display:none'])
                    ->dehydrated(false),
            ])
            ->statePath('.');
    }

    public function verify(): void
    {
        $this->validate([
            'civilId' => ['required', 'string', 'max:20', 'regex:/^[0-9A-Za-z\-]+$/'],
        ]);

        $rateKey = sprintf('verify:%s:%s', request()->ip(), substr($this->civilId, 0, 4));
        if (RateLimiter::tooManyAttempts($rateKey, 10)) {
            $this->resultMessage = 'Too many attempts. Please try again later.';
            return;
        }
        RateLimiter::hit($rateKey, 300);

        $registration = Registration::query()
            ->whereNotNull('civil_id')
            ->whereRaw('LOWER(civil_id) = ?', [mb_strtolower($this->civilId)])
            ->where('renewal_status', 'approved')
            ->first();

        $wasSuccessful = false;
        $validUntilTs = null;

        if ($registration) {
            $validUntil = $registration->card_valid_until;
            $isActive = $validUntil ? now()->lte($validUntil) : false;

            if ($isActive) {
                $wasSuccessful = true;
                $validUntilTs = $validUntil;
                $this->validUntil = optional($validUntil)->timezone(config('app.timezone'))?->toDateString();
                $this->resultMessage = 'Verified: Membership is active.';
            } else {
                $this->resultMessage = 'Not verified: Membership is inactive or expired.';
            }
        } else {
            $this->resultMessage = 'Not verified: No active membership found.';
        }

        VerificationAttempt::create([
            'civil_id' => $this->civilId,
            'ip_address' => request()->ip(),
            'user_agent' => substr((string) request()->userAgent(), 0, 255),
            'was_successful' => $wasSuccessful,
            'verified_until' => $validUntilTs,
        ]);
    }

    public function render()
    {
        return view('livewire.public-verification');
    }
}




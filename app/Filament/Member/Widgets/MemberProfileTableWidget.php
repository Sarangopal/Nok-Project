<?php

namespace App\Filament\Member\Widgets;

use App\Models\Member;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Widgets\TableWidget as BaseTableWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Mail;
use App\Mail\RenewalRequestSubmittedMail;

class MemberProfileTableWidget extends BaseTableWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): Builder|Relation|null
    {
        $member = Auth::guard('members')->user();

        if (!$member) {
            return null;
        }

        return Member::query()->whereKey($member->getKey());
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Profile Overview')
            ->striped()
            ->paginated(false)
            ->columns([
                TextColumn::make('nok_id')
                    ->label('NOK ID')
                    ->default('N/A')
                    ->searchable(false),

                TextColumn::make('email')
                    ->label('Email')
                    ->default('N/A'),

                TextColumn::make('mobile')
                    ->label('Mobile')
                    ->default('N/A'),

                TextColumn::make('address')
                    ->label('Address')
                    ->limit(60)
                    ->toggleable(),

                TextColumn::make('doj')
                    ->label('Joining Date')
                    ->date('d M Y')
                    ->placeholder('N/A'),

                TextColumn::make('card_valid_until')
                    ->label('Renewal Date')
                    ->date('d M Y')
                    ->placeholder('N/A'),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(function ($record) {
                        // Show "Approved" if either login_status OR renewal_status is approved
                        if ($record->login_status === 'approved' || $record->renewal_status === 'approved') {
                            return 'approved';
                        }
                        // Show pending if either is pending
                        if ($record->login_status === 'pending' || $record->renewal_status === 'pending') {
                            return 'pending';
                        }
                        // Show rejected if either is rejected
                        if ($record->login_status === 'rejected' || $record->renewal_status === 'rejected') {
                            return 'rejected';
                        }
                        return 'unknown';
                    })
                    ->colors([
                        'success' => 'approved',
                        'warning' => 'pending',
                        'danger' => 'rejected',
                        'gray' => 'unknown',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst($state)),
            ])
            ->actions([
                // Inline Edit Action
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->color('primary')
                    ->form([
                        TextInput::make('memberName')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('mobile')
                            ->label('Mobile')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                        Textarea::make('address')
                            ->label('Address')
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ])
                    ->fillForm(fn ($record): array => [
                        'memberName' => $record->memberName,
                        'email' => $record->email,
                        'mobile' => $record->mobile,
                        'address' => $record->address,
                    ])
                    ->action(function ($record, array $data): void {
                        $record->update($data);
                        
                        Notification::make()
                            ->title('Profile Updated')
                            ->body('Your profile has been updated successfully.')
                            ->success()
                            ->send();
                    }),

                // Request Renewal Action with Payment Proof
                Action::make('requestRenewal')
                    ->label(fn ($record): string => $this->isExpired($record) ? 'Request Renewal' : 'Request Early Renewal')
                    ->icon('heroicon-o-arrow-path')
                    ->color(fn ($record) => $this->isExpired($record) ? 'danger' : 'warning')
                    ->visible(fn ($record): bool => 
                        ($this->isExpired($record) || $this->isExpiringSoon($record)) 
                        && !$this->isPending($record)
                    )
                    ->form([
                        TextInput::make('memberName')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Review and update if needed'),
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('mobile')
                            ->label('Mobile Number')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                        Textarea::make('address')
                            ->label('Address')
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull(),
                        FileUpload::make('renewal_payment_proof')
                            ->label('Payment Proof Screenshot')
                            ->image()
                            ->maxSize(10240) // 10MB
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
                            ->required()
                            ->disk('public')
                            ->directory('renewal-payment-proofs')
                            ->helperText('Upload payment screenshot (JPG, PNG, WEBP. Max 10MB)')
                            ->columnSpanFull(),
                    ])
                    ->fillForm(fn ($record): array => [
                        'memberName' => $record->memberName,
                        'email' => $record->email,
                        'mobile' => $record->mobile,
                        'address' => $record->address,
                    ])
                    ->modalHeading(fn ($record): string => 
                        $this->isExpired($record) 
                            ? 'Request Membership Renewal' 
                            : 'Request Early Renewal'
                    )
                    ->modalDescription('Review your details, make updates if needed, and upload payment proof.')
                    ->modalSubmitActionLabel('Submit Renewal Request')
                    ->modalWidth('3xl')
                    ->action(function ($record, array $data): void {
                        $record->update([
                            'memberName' => $data['memberName'],
                            'email' => $data['email'],
                            'mobile' => $data['mobile'],
                            'address' => $data['address'],
                            'renewal_payment_proof' => $data['renewal_payment_proof'],
                            'renewal_requested_at' => now(),
                            'renewal_status' => 'pending',
                        ]);

                        // Send email notification to member
                        try {
                            Mail::to($record->email)->send(new RenewalRequestSubmittedMail($record));
                            
                            Notification::make()
                                ->title('Renewal Request Submitted')
                                ->body('Your renewal request has been submitted successfully. Check your email for confirmation.')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            logger()->error('Failed to send renewal request email: ' . $e->getMessage());
                            
                            Notification::make()
                                ->title('Renewal Request Submitted')
                                ->body('Your request was submitted but email notification failed. Please check your inbox or contact support.')
                                ->warning()
                                ->send();
                        }
                    }),

                // Pending Renewal Action (Disabled)
                Action::make('pendingRenewal')
                    ->label('Pending')
                    ->icon('heroicon-o-clock')
                    ->color('info')
                    ->disabled()
                    ->visible(fn ($record): bool => $this->isPending($record)),
            ])
            ->bulkActions([
                // No bulk actions
            ])
           
            ->defaultSort('doj', 'desc');
    }

    private function isExpired($member): bool
    {
        if (!$member->card_valid_until) {
            return false;
        }

        return now()->diffInDays($member->card_valid_until, false) < 0;
    }

    private function isExpiringSoon($member): bool
    {
        if (!$member->card_valid_until) {
            return false;
        }

        $daysLeft = now()->diffInDays($member->card_valid_until, false);

        return $daysLeft >= 0 && $daysLeft <= 30;
    }

    private function isPending($member): bool
    {
        return $member->renewal_status === 'pending' && !empty($member->renewal_requested_at);
    }
}

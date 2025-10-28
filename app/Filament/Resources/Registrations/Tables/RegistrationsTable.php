<?php

namespace App\Filament\Resources\Registrations\Tables;

use Filament\Actions\Action;
use App\Mail\MembershipCardMail;
use App\Models\Registration;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction; // NEW ONE ADDED  
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Mail;
use Filament\Actions\ActionGroup;




class RegistrationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member_type')
                    ->searchable()
                    ->hidden(),
                TextColumn::make('nok_id')
                    ->searchable()
                    ->hidden(),
                TextColumn::make('memberName')
                    ->searchable(),

                TextColumn::make('doj')
                    ->date()
                    ->sortable(),
               
                TextColumn::make('age')
                    ->numeric()
                    ->sortable()
                    ->hidden(),
                TextColumn::make('gender')
                    ->searchable()
                    ->hidden(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),

                TextColumn::make('mobile')
                    ->searchable(),
                TextColumn::make('whatsapp')
                    ->searchable()
                    ->hidden(),
                TextColumn::make('department')
                    ->searchable()
                    ->hidden(),
                TextColumn::make('job_title')
                    ->searchable()
                    ->hidden(),
                TextColumn::make('institution')
                    ->searchable()
                    ->hidden(),
                TextColumn::make('passport')
                    ->searchable()
                    ->hidden(),
                TextColumn::make('civil_id')
                    ->searchable(),
              
                TextColumn::make('blood_group')
                    ->searchable()->hidden(),
                TextColumn::make('phone_india')
                    ->searchable()->hidden(),
                TextColumn::make('nominee_name')
                    ->searchable()->hidden(),
                TextColumn::make('nominee_relation')
                    ->searchable()->hidden(),
                TextColumn::make('nominee_contact')
                    ->searchable()->hidden(),
                TextColumn::make('guardian_name')
                    ->searchable()->hidden(),
                TextColumn::make('guardian_contact')
                    ->searchable()->hidden(),
                TextColumn::make('bank_account_name')
                    ->searchable()->hidden(),
                TextColumn::make('account_number')
                    ->searchable()->hidden(),
                TextColumn::make('ifsc_code')
                    ->searchable()->hidden(),
                TextColumn::make('bank_branch')
                    ->searchable()->hidden(),
                TextColumn::make('login_status')
                    ->badge()
                    ->label('Login Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger'  => 'rejected',
                    ])
                    ->sortable()
                    ->searchable()
                    ->default('pending'),
              
               
                TextColumn::make('renewal_count')
                    ->label('Renewal Count')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),   
                TextColumn::make('last_renewed_at')
                    ->label('Last Renewed At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('card_issued_at')
                    ->label('Card Issued At')
                    ->dateTime()
                    ->hidden(),              
            
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('login_status')
                    ->label('Approval Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default(null),
            ])
         
            ->recordActions([
                Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->login_status === 'pending')
                    ->action(function ($record) {
                        // Auto-generate NOK ID if missing
                        if (empty(trim($record->nok_id ?? ''))) {
                            $next = (int) (Registration::max('id') + 1000);
                            $record->nok_id = 'NOK' . str_pad((string) $next, 6, '0', STR_PAD_LEFT);
                        }

                        // Auto-generate password if missing (for member login)
                        $generatedPassword = null;
                        if (empty($record->password)) {
                            $generatedPassword = 'NOK' . rand(1000, 9999); // Simple password: NOK1234
                            $record->password = bcrypt($generatedPassword);
                        }

                       // Update approval details (NEW REGISTRATION APPROVAL)
                        $record->login_status = 'approved';  // Use login_status for new registrations
                        $record->card_issued_at = now();
                        // For first-time approval, do not set last_renewed_at to avoid showing "renewed"
                        $record->last_renewed_at = null;
                        // For a fresh approval, reset/initialize renewal count if null
                        if ($record->renewal_count === null) {
                            $record->renewal_count = 0;
                        }
                        // Set card validity for the current calendar year (Jan–Dec)
                        $record->card_valid_until = now()->endOfYear();

                        $record->save();

                            try {
                                // Send membership card email (with password if newly generated)
                                $mailData = ['record' => $record, 'password' => $generatedPassword];
                                Mail::to($record->email)->send(new MembershipCardMail($mailData));

                                Notification::make()
                                    ->title('Card Issued Successfully')
                                    ->body('The membership card has been sent via email.')
                                    ->success()
                                    ->send();

                            } catch (\Exception $e) {
                                logger()->error('Mail sending error: ' . $e->getMessage());

                                Notification::make()
                                    ->title('Card Issued, but Notification Failed')
                                    ->body('Error: ' . $e->getMessage())
                                    ->warning()
                                    ->send();
                            }
                        }),

                Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->login_status === 'pending')
                    ->action(function ($record) {
                        $record->login_status = 'rejected';  // Use login_status for new registrations
                        $record->save();
                         // Optional email notification on rejection
                        try {
                            Mail::to($record->email)->send(new MembershipCardMail($record));
                            Notification::make()
                                ->title('Record rejected and mail sent successfully.')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            logger()->error('Mail sending error: ' . $e->getMessage());
                            Notification::make()
                                ->title('Record rejected, but mail sending failed.')
                                ->body('Error: ' . $e->getMessage())
                                ->warning()
                                ->send();
                        }

                    }),
                Action::make('reset_password')
                    ->label('Reset Password')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Reset Member Password')
                    ->modalDescription('Generate a new password and email it to the member?')
                    ->visible(fn ($record) => $record->login_status === 'approved')
                    ->action(function ($record) {
                        $newPassword = 'NOK' . rand(1000, 9999);
                        $record->password = bcrypt($newPassword);
                        $record->save();

                        try {
                            $mailData = ['record' => $record, 'password' => $newPassword];
                            Mail::to($record->email)->send(new MembershipCardMail($mailData));
                            Notification::make()
                                ->title('Password Reset Successfully')
                                ->body("New password sent to {$record->email}")
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Password reset, but email failed')
                                ->body('Error: ' . $e->getMessage())
                                ->warning()
                                ->send();
                        }
                    }),

                Action::make('resend_credentials')
                    ->label('Resend Credentials')
                    ->icon('heroicon-o-envelope')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('Resend Login Credentials')
                    ->modalDescription('Send membership card with current login details to member email?')
                    ->visible(fn ($record) => $record->login_status === 'approved' && !empty($record->password))
                    ->action(function ($record) {
                        try {
                            // Note: Won't include password in email since we can't decrypt it
                            // Use reset password instead to generate new one
                            Mail::to($record->email)->send(new MembershipCardMail($record));
                            Notification::make()
                                ->title('Email Sent Successfully')
                                ->body("Membership card sent to {$record->email}")
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Failed to send email')
                                ->body('Error: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make()
                           
                ]), 

             
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php
namespace App\Filament\Resources\Registrations\Tables;

use Filament\Actions\Action;
use App\Mail\MembershipCardMail;
use App\Models\Registration;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
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
                    ->hidden()
                    ->formatStateUsing(fn ($state) => ucwords(strtolower($state))),

                TextColumn::make('nok_id')
                    ->searchable()
                    ->hidden()
                    ->formatStateUsing(fn ($state) => strtoupper($state)),

                TextColumn::make('memberName')
                    ->searchable()
                    ->formatStateUsing(fn ($state) => ucwords(strtolower($state))),

                TextColumn::make('doj')
                    ->date()
                    ->sortable(),

                TextColumn::make('age')
                    ->numeric()
                    ->sortable()
                    ->hidden(),

                TextColumn::make('gender')
                    ->searchable()
                    ->hidden()
                    ->formatStateUsing(fn ($state) => ucwords(strtolower($state))),

                TextColumn::make('email')
                    ->label('Email Address')
                    ->searchable()
                    ->formatStateUsing(fn ($state) => strtolower($state)),

                TextColumn::make('mobile')
                    ->searchable()
                    ->formatStateUsing(fn ($state) => ucwords(strtolower($state))),

                TextColumn::make('whatsapp')
                    ->searchable()
                    ->hidden()
                    ->formatStateUsing(fn ($state) => ucwords(strtolower($state))),

                TextColumn::make('department')
                    ->searchable()
                    ->hidden()
                    ->formatStateUsing(fn ($state) => ucwords(strtolower($state))),

                TextColumn::make('job_title')
                    ->searchable()
                    ->hidden()
                    ->formatStateUsing(fn ($state) => ucwords(strtolower($state))),

                TextColumn::make('institution')
                    ->searchable()
                    ->hidden()
                    ->formatStateUsing(fn ($state) => ucwords(strtolower($state))),

                TextColumn::make('passport')
                    ->searchable()
                    ->hidden()
                    ->formatStateUsing(fn ($state) => strtoupper($state)),

                TextColumn::make('civil_id')
                    ->searchable()
                    ->formatStateUsing(fn ($state) => strtoupper($state)),

                TextColumn::make('blood_group')
                    ->searchable()
                    ->hidden()
                    ->formatStateUsing(fn ($state) => strtoupper($state)),

                TextColumn::make('phone_india')
                    ->searchable()
                    ->hidden()
                    ->formatStateUsing(fn ($state) => ucwords(strtolower($state))),

                TextColumn::make('nominee_name')
                    ->searchable()
                    ->hidden()
                    ->formatStateUsing(fn ($state) => ucwords(strtolower($state))),

                TextColumn::make('nominee_relation')
                    ->searchable()
                    ->hidden()
                    ->formatStateUsing(fn ($state) => ucwords(strtolower($state))),

                TextColumn::make('nominee_contact')
                    ->searchable()
                    ->hidden()
                    ->formatStateUsing(fn ($state) => ucwords(strtolower($state))),

                TextColumn::make('guardian_name')
                    ->searchable()
                    ->hidden()
                    ->formatStateUsing(fn ($state) => ucwords(strtolower($state))),

                TextColumn::make('guardian_contact')
                    ->searchable()
                    ->hidden()
                    ->formatStateUsing(fn ($state) => ucwords(strtolower($state))),

                TextColumn::make('bank_account_name')
                    ->searchable()
                    ->hidden()
                    ->formatStateUsing(fn ($state) => ucwords(strtolower($state))),

                TextColumn::make('account_number')
                    ->searchable()
                    ->hidden(),

                TextColumn::make('ifsc_code')
                    ->searchable()
                    ->hidden()
                    ->formatStateUsing(fn ($state) => strtoupper($state)),

                TextColumn::make('bank_branch')
                    ->searchable()
                    ->hidden()
                    ->formatStateUsing(fn ($state) => ucwords(strtolower($state))),

                TextColumn::make('login_status')
                    ->badge()
                    ->label('Login Status')
                    ->formatStateUsing(fn ($state) => strtoupper($state)) // 🔠 All caps (APPROVED)
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
                        if (empty(trim($record->nok_id ?? ''))) {
                            $next = (int)(Registration::max('id') + 1000);
                            $record->nok_id = 'NOK' . str_pad((string)$next, 6, '0', STR_PAD_LEFT);
                        }

                        $generatedPassword = null;
                        if (empty($record->password)) {
                            $generatedPassword = 'NOK' . rand(1000, 9999);
                            $record->password = bcrypt($generatedPassword);
                        }

                        $record->login_status = 'approved';
                        $record->card_issued_at = now();
                        $record->last_renewed_at = null;
                        $record->renewal_count = $record->renewal_count ?? 0;
                        $record->card_valid_until = $record->computeCalendarYearValidity();
                        $record->save();

                        try {
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
                        $record->login_status = 'rejected';
                        $record->save();

                        try {
                            Mail::to($record->email)->send(new MembershipCardMail($record));
                            Notification::make()
                                ->title('Record Rejected and Mail Sent Successfully.')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            logger()->error('Mail sending error: ' . $e->getMessage());
                            Notification::make()
                                ->title('Record Rejected, but Mail Failed.')
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
                                ->title('Password Reset, but Email Failed')
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
                            Mail::to($record->email)->send(new MembershipCardMail($record));
                            Notification::make()
                                ->title('Email Sent Successfully')
                                ->body("Membership card sent to {$record->email}")
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Failed to Send Email')
                                ->body('Error: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

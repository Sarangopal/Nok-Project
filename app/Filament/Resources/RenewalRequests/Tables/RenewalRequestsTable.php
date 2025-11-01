<?php

namespace App\Filament\Resources\RenewalRequests\Tables;

use App\Mail\MembershipCardMail;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

class RenewalRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nok_id')->searchable()->label('NOK ID'),
                TextColumn::make('memberName')->searchable()->label('Member Name')
                    ->description(fn ($record) => 'Updated: ' . ($record->memberName ?? 'N/A')),
                TextColumn::make('email')->searchable()
                    ->description(fn ($record) => 'Updated: ' . ($record->email ?? 'N/A')),
                TextColumn::make('mobile')->searchable()
                    ->description(fn ($record) => 'Updated: ' . ($record->mobile ?? 'N/A')),
                TextColumn::make('address')->searchable()
                    ->description(fn ($record) => 'Updated: ' . ($record->address ?? 'N/A'))
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->address)->hidden(),
                TextColumn::make('civil_id')->searchable()->label('Civil ID'),
                
                ImageColumn::make('renewal_payment_proof')
                    ->label('Payment Proof')
                    ->disk('public')
                    ->square()
                    ->defaultImageUrl(url('/images/no-image.png'))
                    ->extraImgAttributes(['loading' => 'lazy'])
                    ->tooltip('Click to view full image')->hidden(),
                
                TextColumn::make('renewal_requested_at')
                    ->label('Requested At')
                    ->dateTime('d-m-Y H:i')
                    ->sortable(),
                
                BadgeColumn::make('renewal_status')
                    ->label('Status')
                    ->formatStateUsing(fn (?string $state): string => $state ? ucfirst($state) : '')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                    ])
                    ->sortable(),
                
                BadgeColumn::make('card_valid_until')
                    ->label('Current Expiry')
                    ->color(function ($state) {
                        if (!$state) return null;
                        $days = (int) now()->diffInDays(Carbon::parse($state), false);
                        if ($days < 0) return 'danger';      // expired -> red
                        if ($days <= 30) return 'warning';   // within 30 days -> yellow
                        return 'success';                    // otherwise valid -> green
                    })
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '-';
                        $days = (int) now()->diffInDays(Carbon::parse($state), false);
                        if ($days < 0) return 'Expired';
                        if ($days <= 30) return "Expiring Soon ({$days} days)";
                        return 'Valid';
                    })
                    ->sortable()
                    ->getStateUsing(fn($record) => $record->card_valid_until),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Approve Renewal')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Renewal Request')
                    ->modalDescription(function ($record) {
                        $currentExpiry = $record->card_valid_until ? \Carbon\Carbon::parse($record->card_valid_until)->format('M d, Y') : 'N/A';
                        $newExpiry = $record->card_valid_until ? \Carbon\Carbon::parse($record->card_valid_until)->addYear()->endOfYear()->format('M d, Y') : 'N/A';
                        return "Current expiry: {$currentExpiry}. New expiry will be: {$newExpiry} (extended by 1 year).";
                    })
                    ->visible(fn ($record) => $record->renewal_status === 'pending')
                    ->action(function ($record) {
                        // Approve the renewal
                        $record->renewal_status = 'approved';
                        $record->last_renewed_at = now();
                        $record->renewal_count = ($record->renewal_count ?? 0) + 1;
                        
                        // For RENEWALS: Extend card validity by 1 year from current expiry
                        // Example: If current expiry is Dec 31, 2025 -> new expiry will be Dec 31, 2026
                        // This ensures members get a full year extension when renewing
                        $record->card_valid_until = $record->computeCalendarYearValidity(null, true);
                        
                        $record->save();
                        
                        $record->refresh();

                        try {
                            // Send updated card via email
                            Mail::to($record->email)->send(new MembershipCardMail(['record' => $record]));
                            Notification::make()
                                ->title('Renewal Approved')
                                ->body('Renewal approved and card sent to member email.')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            logger()->error('Mail sending error: ' . $e->getMessage());
                            Notification::make()
                                ->title('Renewal approved, but email failed')
                                ->body('Error: ' . $e->getMessage())
                                ->warning()
                                ->send();
                        }
                    }),

                // Action::make('reject')
                //     ->label('Reject')
                //     ->color('danger')
                //     ->icon('heroicon-o-x-circle')
                //     ->requiresConfirmation()
                //     ->modalHeading('Reject Renewal Request')
                //     ->modalDescription('Are you sure you want to reject this renewal request?')
                //     ->visible(fn ($record) => $record->renewal_status === 'pending')
                //     ->action(function ($record) {
                //         // Reset renewal request
                //         $record->renewal_requested_at = null;
                //         $record->renewal_payment_proof = null;
                //         $record->save();

                //         try {
                //             // Send rejection email
                //             Mail::to($record->email)->send(new MembershipCardMail(['record' => $record]));
                //             Notification::make()
                //                 ->title('Renewal Rejected')
                //                 ->body('Renewal request rejected and email sent to member.')
                //                 ->success()
                //                 ->send();
                //         } catch (\Exception $e) {
                //             logger()->error('Mail sending error: ' . $e->getMessage());
                //             Notification::make()
                //                 ->title('Renewal rejected, but email failed')
                //                 ->body('Error: ' . $e->getMessage())
                //                 ->warning()
                //                 ->send();
                //         }
                //     }),

                Action::make('viewDetails')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading(fn ($record) => '📋 Renewal Request - ' . $record->memberName)
                    ->modalWidth('4xl')
                    ->modalContent(fn ($record) => view('filament.modals.renewal-request-details', ['record' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),
                    
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->filters([
                Filter::make('pending')
                    ->label('Pending Only')
                    ->query(fn (Builder $query) => $query->where('renewal_status', 'pending')),
                Filter::make('approved')
                    ->label('Approved')
                    ->query(fn (Builder $query) => $query->where('renewal_status', 'approved')),
            ]);
    }
}


<?php

namespace App\Filament\Resources\Renewals\Tables;

use App\Mail\MembershipCardMail;
// use Filament\Tables;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction; // NEW ONE ADDED   
use Filament\Notifications\Notification;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

class RenewalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member_type')->badge(),
                TextColumn::make('nok_id')->searchable(),
                TextColumn::make('doj')->date()->sortable(),
                TextColumn::make('memberName')->searchable(),
                TextColumn::make('age')->numeric()->sortable()->hidden(),
                TextColumn::make('gender')->searchable()->hidden(),
                TextColumn::make('email')->label('Email address')->searchable(),
                TextColumn::make('mobile')->searchable()->hidden(),
                TextColumn::make('whatsapp')->searchable() ->hidden(),
                TextColumn::make('department')->searchable()->hidden(),
                TextColumn::make('job_title')->searchable()->hidden(),
                TextColumn::make('institution')->searchable()->hidden(),
                TextColumn::make('passport')->searchable()->hidden(),
                TextColumn::make('civil_id')->searchable(),                
                TextColumn::make('blood_group')->searchable()->hidden(),
                TextColumn::make('phone_india')->searchable()->hidden(),
                TextColumn::make('nominee_name')->searchable()->hidden(),
                TextColumn::make('nominee_relation')->searchable()->hidden(),
                TextColumn::make('nominee_contact')->searchable()->hidden(),
                TextColumn::make('guardian_name')->searchable()->hidden(),
                TextColumn::make('guardian_contact')->searchable()->hidden()    ,
                TextColumn::make('bank_account_name')->searchable()->hidden(),
                TextColumn::make('account_number')->searchable()->hidden(),
                TextColumn::make('ifsc_code')->searchable()->hidden(),
                TextColumn::make('bank_branch')->searchable()->hidden(),
                BadgeColumn::make('is_renewal_due')
                    ->label('Renewal Due')
                    ->colors([
                        'success' => fn($state) => $state === 'Renewed',           // green
                        'warning' => fn($state) => $state === 'Renewal Pending',   // yellow/orange
                        'danger'  => fn($state) => $state === 'Renewal Due',       // red
                        'gray'    => fn($state) => $state === 'No Expiry Date',    // gray for missing dates
                    ])
                    ->getStateUsing(function($record) {
                        // If no expiry date set, show as missing data
                        if (!$record->card_valid_until) {
                            return 'No Expiry Date';
                        }
                        
                        $expiryDate = Carbon::parse($record->card_valid_until);
                        $daysUntilExpiry = now()->diffInDays($expiryDate, false);
                        
                        // Check if card is expired or expiring within 30 days
                        $isExpiredOrExpiring = $daysUntilExpiry <= 30;
                        
                        if (!$isExpiredOrExpiring) {
                            // Card is still valid (more than 30 days)
                            return 'Renewed';
                        }
                        
                        // Card is expired or expiring soon
                        // Check if member submitted a NEW renewal request recently
                        $hasRecentRenewalRequest = $record->renewal_requested_at && 
                                                   Carbon::parse($record->renewal_requested_at)->isAfter($record->last_renewed_at ?? $record->card_issued_at);
                        
                        if ($hasRecentRenewalRequest && $record->renewal_status === 'pending') {
                            // Member submitted renewal request, waiting for admin approval
                            return 'Renewal Pending';
                        }
                        
                        // Card expired/expiring but no new renewal request submitted yet
                        return 'Renewal Due';
                    }),
                BadgeColumn::make('renewal_request_status')
                    ->label('Action Needed')
                    ->colors([
                        'danger' => 'Needs Renewal Request',
                        'warning' => 'Request Pending Approval',
                        'success' => 'Request Recently Approved',
                    ])
                    ->getStateUsing(function($record) {
                        // Check if member has submitted a NEW renewal request for current expiry
                        $hasRecentRequest = $record->renewal_requested_at && 
                                          Carbon::parse($record->renewal_requested_at)->isAfter($record->last_renewed_at ?? $record->card_issued_at);
                        
                        if ($hasRecentRequest && $record->renewal_status === 'pending') {
                            return 'Request Pending Approval';
                        }
                        
                        // Check if recently approved (within last 7 days)
                        if ($record->last_renewed_at && Carbon::parse($record->last_renewed_at)->gt(now()->subDays(7))) {
                            return 'Request Recently Approved';
                        }
                        
                        // Default: member needs to submit renewal request
                        return 'Needs Renewal Request';
                    }),
                BadgeColumn::make('card_valid_until')
                ->label('Expiry Date')
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
                
                // TextColumn::make('renewal_status')
                //     ->badge()
                //     ->colors([
                //         'warning' => 'pending',
                //         'success' => 'approved',
                //         'danger'  => 'rejected',
                //     ])
                //     ->sortable()
                //     ->searchable(),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
                
            ])
           ->headerActions([
            Action::make('custom')
                ->label('')
                ->view('legend')
                ->action(fn () => null),
           ])
            ->recordActions([
                // Only View, Edit, and Delete actions - No Approve/Reject here
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->filters([
                   Filter::make('Expired')
                    ->query(fn (Builder $query) => $query->whereDate('card_valid_until', '<', now())),
                    Filter::make('Expiring Soon')
                    ->query(fn (Builder $query) => $query->whereBetween('card_valid_until', [now(), now()->addDays(30)])),
            ]);
    }
}
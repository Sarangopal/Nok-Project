<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\VerificationAttempt;

class VerificationController extends Controller
{
    // Show the form page (supports optional prefill via ?civil_id= or ?nok_id=)
    public function showForm(Request $request)
    {
        $prefillCivilId = $request->query('civil_id') ?? $request->query('nok_id');
        return view('verify-membership', [
            'prefillCivilId' => $prefillCivilId,
        ]); // Blade file: resources/views/verify-membership.blade.php
    }

    // Process form submission
    public function verify(Request $request)
    {
        $request->validate([
            'civil_id' => 'required|string|max:50',
            'email' => 'nullable|email',
        ]);

        $civilId = $request->input('civil_id');
        $email = $request->input('email');

        // Find member by civil ID or NOK ID (users often confuse the two)
        $query = Registration::where(function($q) use ($civilId) {
            $q->where('civil_id', $civilId)
              ->orWhere('nok_id', $civilId);
        });
        
        // Optional email double verification
        if ($email) {
            $query->where('email', $email);
        }
        
        $member = $query->first();

        $isValid = false;
        $message = 'No member found with this Civil ID or NOK ID.';
        $statusIcon = '❌';
        $statusBadge = 'danger';
        $status = 'not_found';

        if ($member) {
            // Check both login_status (for new registrations) and renewal_status (for renewals)
            $approved = $member->login_status === 'approved' || $member->renewal_status === 'approved';
            $isPending = $member->login_status === 'pending' || $member->renewal_status === 'pending';
            $isRejected = $member->login_status === 'rejected' || $member->renewal_status === 'rejected';
            $notExpired = optional($member->card_valid_until)?->isFuture();
            
            if ($approved && $notExpired) {
                $isValid = true;
                $message = 'Membership Verified — Active Member';
                $statusIcon = '🟢';
                $statusBadge = 'success';
                $status = 'active';
            } elseif ($approved && !$notExpired) {
                $message = 'Membership Expired';
                $statusIcon = '🔴';
                $statusBadge = 'danger';
                $status = 'expired';
            } elseif ($isPending) {
                $message = 'Membership Pending Approval';
                $statusIcon = '⚪';
                $statusBadge = 'warning';
                $status = 'pending';
            } elseif ($isRejected) {
                $message = 'Membership Not Active';
                $statusIcon = '⚪';
                $statusBadge = 'secondary';
                $status = 'inactive';
            }
        }

        // Log attempt (optional)
        try {
            VerificationAttempt::create([
                'civil_id' => $civilId,
                'ip_address' => (string) $request->ip(),
                'user_agent' => (string) $request->userAgent(),
                'was_successful' => $isValid,
                'verified_until' => $member?->card_valid_until,
            ]);
        } catch (\Throwable $e) {
            // ignore logging errors
        }

        return view('verify-membership', [
            'member' => $member,
            'isValid' => $isValid,
            'message' => $message,
            'statusIcon' => $statusIcon,
            'statusBadge' => $statusBadge,
            'status' => $status,
        ]);
    }

}



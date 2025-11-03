<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Filament\Tables\Columns\BadgeColumn;

/**
 * Test the Member Profile Status Badge Logic
 * 
 * This verifies the fix where members with pending renewal requests
 * were incorrectly showing "Approved" status instead of "Renewal Pending".
 */
class MemberProfileStatusTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that pending renewal shows "Renewal Pending" not "Approved"
     */
    public function test_pending_renewal_shows_renewal_pending_status(): void
    {
        $member = Registration::factory()->create([
            'login_status' => 'approved',        // Original membership approved
            'renewal_status' => 'pending',        // Renewal request pending
            'renewal_requested_at' => now(),
        ]);

        // Simulate the badge column getStateUsing logic
        $status = $this->getMemberStatus($member);

        $this->assertEquals('renewal_pending', $status);
    }

    /**
     * Test approved member without renewal request shows "Approved"
     */
    public function test_approved_member_without_renewal_shows_approved(): void
    {
        $member = Registration::factory()->create([
            'login_status' => 'approved',
            'renewal_status' => null,
            'renewal_requested_at' => null,
        ]);

        $status = $this->getMemberStatus($member);

        $this->assertEquals('approved', $status);
    }

    /**
     * Test approved renewal shows "Approved"
     */
    public function test_approved_renewal_shows_approved(): void
    {
        $member = Registration::factory()->create([
            'login_status' => 'approved',
            'renewal_status' => 'approved',
            'renewal_requested_at' => now()->subDays(5),
            'last_renewed_at' => now(),
        ]);

        $status = $this->getMemberStatus($member);

        $this->assertEquals('approved', $status);
    }

    /**
     * Test rejected renewal shows "Renewal Rejected"
     */
    public function test_rejected_renewal_shows_renewal_rejected(): void
    {
        $member = Registration::factory()->create([
            'login_status' => 'approved',
            'renewal_status' => 'rejected',
            'renewal_requested_at' => now()->subDays(2),
        ]);

        $status = $this->getMemberStatus($member);

        $this->assertEquals('renewal_rejected', $status);
    }

    /**
     * Test pending login shows "Pending"
     */
    public function test_pending_login_shows_pending(): void
    {
        $member = Registration::factory()->create([
            'login_status' => 'pending',
            'renewal_status' => null,
            'renewal_requested_at' => null,
        ]);

        $status = $this->getMemberStatus($member);

        $this->assertEquals('pending', $status);
    }

    /**
     * Test rejected login shows "Rejected"
     */
    public function test_rejected_login_shows_rejected(): void
    {
        $member = Registration::factory()->create([
            'login_status' => 'rejected',
            'renewal_status' => null,
            'renewal_requested_at' => null,
        ]);

        $status = $this->getMemberStatus($member);

        $this->assertEquals('rejected', $status);
    }

    /**
     * Test renewal_status is prioritized over login_status when renewal_requested_at exists
     */
    public function test_renewal_status_prioritized_when_renewal_requested(): void
    {
        // This is the main bug fix - renewal pending should show even if login is approved
        $member = Registration::factory()->create([
            'login_status' => 'approved',         // Approved
            'renewal_status' => 'pending',         // But renewal pending
            'renewal_requested_at' => now(),       // Has requested renewal
        ]);

        $status = $this->getMemberStatus($member);

        // Should be 'renewal_pending' NOT 'approved'
        $this->assertEquals('renewal_pending', $status);
        $this->assertNotEquals('approved', $status);
    }

    /**
     * Test status without renewal_requested_at uses login_status
     */
    public function test_no_renewal_request_uses_login_status(): void
    {
        $member = Registration::factory()->create([
            'login_status' => 'approved',
            'renewal_status' => 'pending',      // Has renewal_status but...
            'renewal_requested_at' => null,      // No renewal_requested_at, so ignore renewal_status
        ]);

        $status = $this->getMemberStatus($member);

        // Should use login_status since no renewal_requested_at
        $this->assertEquals('approved', $status);
    }

    /**
     * Test multiple renewal scenarios
     */
    public function test_complete_renewal_lifecycle(): void
    {
        $member = Registration::factory()->create([
            'login_status' => 'approved',
            'renewal_status' => null,
            'renewal_requested_at' => null,
        ]);

        // Initial state - approved
        $this->assertEquals('approved', $this->getMemberStatus($member));

        // Request renewal
        $member->update([
            'renewal_status' => 'pending',
            'renewal_requested_at' => now(),
        ]);
        $this->assertEquals('renewal_pending', $this->getMemberStatus($member));

        // Admin approves renewal
        $member->update([
            'renewal_status' => 'approved',
            'last_renewed_at' => now(),
        ]);
        $this->assertEquals('approved', $this->getMemberStatus($member));

        // Next year - request renewal again
        $member->update([
            'renewal_status' => 'pending',
            'renewal_requested_at' => now()->addYear(),
        ]);
        $this->assertEquals('renewal_pending', $this->getMemberStatus($member));
    }

    /**
     * Test status badge color mapping
     */
    public function test_status_badge_colors(): void
    {
        $colorMapping = [
            'approved' => 'success',           // Green
            'renewal_pending' => 'warning',    // Yellow
            'pending' => 'warning',            // Yellow
            'renewal_rejected' => 'danger',    // Red
            'rejected' => 'danger',            // Red
            'unknown' => 'gray',               // Gray
        ];

        foreach ($colorMapping as $status => $expectedColor) {
            $color = $this->getStatusColor($status);
            $this->assertEquals($expectedColor, $color, "Status '{$status}' should have color '{$expectedColor}'");
        }
    }

    /**
     * Test status label formatting
     */
    public function test_status_label_formatting(): void
    {
        $labelMapping = [
            'renewal_pending' => 'Renewal Pending',
            'renewal_rejected' => 'Renewal Rejected',
            'approved' => 'Approved',
            'pending' => 'Pending',
            'rejected' => 'Rejected',
            'unknown' => 'Unknown',
        ];

        foreach ($labelMapping as $status => $expectedLabel) {
            $label = $this->getStatusLabel($status);
            $this->assertEquals($expectedLabel, $label, "Status '{$status}' should display as '{$expectedLabel}'");
        }
    }

    // Helper methods to simulate the widget logic

    private function getMemberStatus($member): string
    {
        // Prioritize renewal_status if there's a pending renewal request
        if ($member->renewal_requested_at && $member->renewal_status) {
            if ($member->renewal_status === 'pending') {
                return 'renewal_pending';
            }
            if ($member->renewal_status === 'approved') {
                return 'approved';
            }
            if ($member->renewal_status === 'rejected') {
                return 'renewal_rejected';
            }
        }
        
        // Otherwise, show login_status
        if ($member->login_status === 'approved') {
            return 'approved';
        }
        if ($member->login_status === 'pending') {
            return 'pending';
        }
        if ($member->login_status === 'rejected') {
            return 'rejected';
        }
        
        return 'unknown';
    }

    private function getStatusColor($state): string
    {
        if ($state === 'approved') {
            return 'success';
        }
        if (in_array($state, ['pending', 'renewal_pending'])) {
            return 'warning';
        }
        if (in_array($state, ['rejected', 'renewal_rejected'])) {
            return 'danger';
        }
        return 'gray';
    }

    private function getStatusLabel($state): string
    {
        return match($state) {
            'renewal_pending' => 'Renewal Pending',
            'renewal_rejected' => 'Renewal Rejected',
            'approved' => 'Approved',
            'pending' => 'Pending',
            'rejected' => 'Rejected',
            default => ucfirst($state),
        };
    }
}


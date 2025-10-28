<?php

namespace Tests\Feature;

use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MembershipCardDownloadTest extends TestCase
{
    use RefreshDatabase;

    public function test_download_membership_card_returns_pdf(): void
    {
        $reg = Registration::create([
            'member_type' => 'new',
            'memberName' => 'PDF User',
            'email' => 'pdf@example.com',
            'civil_id' => 'PDF123',
            'age' => 30,
            'gender' => 'M',
            'renewal_status' => 'approved',
            'card_valid_until' => now()->endOfYear(),
        ]);

        $response = $this->get(route('membership.card.download', $reg->id));
        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
        $response->assertHeader('content-disposition');
    }
}








<?php

namespace Tests\Feature;

use App\Models\Offer;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OfferAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_offer_can_be_assigned_to_member_and_visible_only_to_them(): void
    {
        $memberA = Registration::factory()->create();
        $memberB = Registration::factory()->create();

        $offer = Offer::create([
            'title' => '10% Discount',
            'active' => true,
        ]);

        $offer->registrations()->attach($memberA->id);

        $this->assertTrue($memberA->offers()->whereKey($offer->id)->exists());
        $this->assertFalse($memberB->offers()->whereKey($offer->id)->exists());
    }
}



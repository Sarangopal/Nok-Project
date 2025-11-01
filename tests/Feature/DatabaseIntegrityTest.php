<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Registration;
use App\Models\Renewal;
use App\Models\Event;
use App\Models\Offer;
use App\Models\ContactMessage;
use App\Models\VerificationAttempt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseIntegrityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function users_table_has_required_columns()
    {
        $this->assertTrue(Schema::hasTable('users'));
        $this->assertTrue(Schema::hasColumns('users', [
            'id', 'name', 'email', 'password', 'created_at', 'updated_at'
        ]));
    }

    /** @test */
    public function registrations_table_has_required_columns()
    {
        $this->assertTrue(Schema::hasTable('registrations'));
        $this->assertTrue(Schema::hasColumns('registrations', [
            'id', 'memberName', 'civil_id', 'email', 'whatsapp_number',
            'registration_type', 'renewal_status', 'card_valid_until',
            'created_at', 'updated_at'
        ]));
    }

    /** @test */
    public function renewals_table_has_required_columns()
    {
        $this->assertTrue(Schema::hasTable('renewals'));
        $this->assertTrue(Schema::hasColumns('renewals', [
            'id', 'registration_id', 'renewal_date', 'status',
            'created_at', 'updated_at'
        ]));
    }

    /** @test */
    public function events_table_has_required_columns()
    {
        $this->assertTrue(Schema::hasTable('events'));
        $this->assertTrue(Schema::hasColumns('events', [
            'id', 'title', 'slug', 'description', 'event_date',
            'created_at', 'updated_at'
        ]));
    }

    /** @test */
    public function offers_table_has_required_columns()
    {
        $this->assertTrue(Schema::hasTable('offers'));
        $this->assertTrue(Schema::hasColumns('offers', [
            'id', 'title', 'description', 'valid_from', 'valid_until',
            'created_at', 'updated_at'
        ]));
    }

    /** @test */
    public function contact_messages_table_has_required_columns()
    {
        $this->assertTrue(Schema::hasTable('contact_messages'));
        $this->assertTrue(Schema::hasColumns('contact_messages', [
            'id', 'name', 'email', 'message',
            'created_at', 'updated_at'
        ]));
    }

    /** @test */
    public function verification_attempts_table_exists()
    {
        $this->assertTrue(Schema::hasTable('verification_attempts'));
    }

    /** @test */
    public function registration_civil_id_is_unique()
    {
        Registration::create([
            'memberName' => 'First Member',
            'civil_id' => '12345678901',
            'email' => 'first@test.com',
            'whatsapp_number' => '+96512345678',
            'registration_type' => 'New Member',
            'renewal_status' => 'pending',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Registration::create([
            'memberName' => 'Second Member',
            'civil_id' => '12345678901', // Duplicate
            'email' => 'second@test.com',
            'whatsapp_number' => '+96587654321',
            'registration_type' => 'New Member',
            'renewal_status' => 'pending',
        ]);
    }

    /** @test */
    public function registration_email_is_unique()
    {
        Registration::create([
            'memberName' => 'First Member',
            'civil_id' => '11111111111',
            'email' => 'same@test.com',
            'whatsapp_number' => '+96511111111',
            'registration_type' => 'New Member',
            'renewal_status' => 'pending',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Registration::create([
            'memberName' => 'Second Member',
            'civil_id' => '22222222222',
            'email' => 'same@test.com', // Duplicate
            'whatsapp_number' => '+96522222222',
            'registration_type' => 'New Member',
            'renewal_status' => 'pending',
        ]);
    }

    /** @test */
    public function user_email_is_unique()
    {
        User::create([
            'name' => 'First User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        User::create([
            'name' => 'Second User',
            'email' => 'admin@test.com', // Duplicate
            'password' => bcrypt('password'),
        ]);
    }

    /** @test */
    public function registration_has_default_renewal_status()
    {
        $registration = Registration::create([
            'memberName' => 'Test Member',
            'civil_id' => '12345678901',
            'email' => 'test@test.com',
            'whatsapp_number' => '+96512345678',
            'registration_type' => 'New Member',
        ]);

        $this->assertNotNull($registration->renewal_status);
        $this->assertEquals('pending', $registration->renewal_status);
    }

    /** @test */
    public function renewal_belongs_to_registration()
    {
        $registration = Registration::create([
            'memberName' => 'Test Member',
            'civil_id' => '12345678901',
            'email' => 'test@test.com',
            'whatsapp_number' => '+96512345678',
            'registration_type' => 'New Member',
            'renewal_status' => 'approved',
        ]);

        $renewal = Renewal::create([
            'registration_id' => $registration->id,
            'renewal_date' => now(),
            'status' => 'active',
        ]);

        $this->assertEquals($registration->id, $renewal->registration_id);
        $this->assertInstanceOf(Registration::class, $renewal->registration);
    }

    /** @test */
    public function soft_deletes_work_if_implemented()
    {
        // Check if soft deletes column exists
        if (Schema::hasColumn('registrations', 'deleted_at')) {
            $registration = Registration::create([
                'memberName' => 'Test Member',
                'civil_id' => '12345678901',
                'email' => 'test@test.com',
                'whatsapp_number' => '+96512345678',
                'registration_type' => 'New Member',
                'renewal_status' => 'pending',
            ]);

            $registration->delete();

            $this->assertSoftDeleted($registration);
        } else {
            $this->assertTrue(true); // Skip if not implemented
        }
    }

    /** @test */
    public function timestamps_are_automatically_managed()
    {
        $registration = Registration::create([
            'memberName' => 'Test Member',
            'civil_id' => '12345678901',
            'email' => 'test@test.com',
            'whatsapp_number' => '+96512345678',
            'registration_type' => 'New Member',
            'renewal_status' => 'pending',
        ]);

        $this->assertNotNull($registration->created_at);
        $this->assertNotNull($registration->updated_at);

        $originalUpdatedAt = $registration->updated_at;

        // Wait a moment and update
        sleep(1);
        $registration->memberName = 'Updated Name';
        $registration->save();

        $this->assertTrue($registration->updated_at->gt($originalUpdatedAt));
    }

    /** @test */
    public function database_can_store_arabic_text()
    {
        $registration = Registration::create([
            'memberName' => 'اسم عربي', // Arabic name
            'civil_id' => '12345678901',
            'email' => 'test@test.com',
            'whatsapp_number' => '+96512345678',
            'registration_type' => 'New Member',
            'renewal_status' => 'pending',
        ]);

        $this->assertEquals('اسم عربي', $registration->memberName);
    }

    /** @test */
    public function contact_message_stores_all_data_correctly()
    {
        $message = ContactMessage::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '+96512345678',
            'message' => 'This is a test message with special chars: #@$%',
        ]);

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'john@example.com',
            'message' => 'This is a test message with special chars: #@$%',
        ]);
    }

    /** @test */
    public function event_slug_should_be_unique()
    {
        Event::create([
            'title' => 'First Event',
            'slug' => 'test-event',
            'description' => 'First description',
            'event_date' => now(),
            'location' => 'Location 1',
            'is_published' => true,
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Event::create([
            'title' => 'Second Event',
            'slug' => 'test-event', // Duplicate slug
            'description' => 'Second description',
            'event_date' => now(),
            'location' => 'Location 2',
            'is_published' => true,
        ]);
    }

    /** @test */
    public function offer_dates_can_be_queried()
    {
        $activeOffer = Offer::create([
            'title' => 'Active Offer',
            'description' => 'Currently active',
            'valid_from' => now()->subWeek(),
            'valid_until' => now()->addWeek(),
            'is_active' => true,
        ]);

        $expiredOffer = Offer::create([
            'title' => 'Expired Offer',
            'description' => 'Already expired',
            'valid_from' => now()->subMonth(),
            'valid_until' => now()->subWeek(),
            'is_active' => false,
        ]);

        // Query active offers
        $activeOffers = Offer::where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->get();

        $this->assertTrue($activeOffers->contains($activeOffer));
        $this->assertFalse($activeOffers->contains($expiredOffer));
    }

    /** @test */
    public function verification_attempts_are_logged()
    {
        $attempt = VerificationAttempt::create([
            'civil_id' => '12345678901',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Test Browser',
            'success' => true,
        ]);

        $this->assertDatabaseHas('verification_attempts', [
            'civil_id' => '12345678901',
            'success' => true,
        ]);
    }

    /** @test */
    public function database_transactions_work_correctly()
    {
        DB::beginTransaction();

        try {
            Registration::create([
                'memberName' => 'Transaction Test',
                'civil_id' => '12345678901',
                'email' => 'transaction@test.com',
                'whatsapp_number' => '+96512345678',
                'registration_type' => 'New Member',
                'renewal_status' => 'pending',
            ]);

            DB::rollBack();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        // Should not exist due to rollback
        $this->assertDatabaseMissing('registrations', [
            'email' => 'transaction@test.com',
        ]);
    }

    /** @test */
    public function member_card_expiry_dates_are_calculated_correctly()
    {
        $registration = Registration::create([
            'memberName' => 'Test Member',
            'civil_id' => '12345678901',
            'email' => 'test@test.com',
            'whatsapp_number' => '+96512345678',
            'registration_type' => 'New Member',
            'renewal_status' => 'approved',
            'card_valid_until' => now()->addYear(),
        ]);

        $this->assertTrue($registration->card_valid_until->isFuture());
        $this->assertFalse($registration->card_valid_until->isPast());
    }

    /** @test */
    public function expired_members_can_be_queried()
    {
        // Create expired member
        $expiredMember = Registration::create([
            'memberName' => 'Expired Member',
            'civil_id' => '11111111111',
            'email' => 'expired@test.com',
            'whatsapp_number' => '+96511111111',
            'registration_type' => 'Renewal',
            'renewal_status' => 'approved',
            'card_valid_until' => now()->subDay(),
        ]);

        // Create active member
        $activeMember = Registration::create([
            'memberName' => 'Active Member',
            'civil_id' => '22222222222',
            'email' => 'active@test.com',
            'whatsapp_number' => '+96522222222',
            'registration_type' => 'New Member',
            'renewal_status' => 'approved',
            'card_valid_until' => now()->addYear(),
        ]);

        $expiredMembers = Registration::where('renewal_status', 'approved')
            ->where('card_valid_until', '<', now())
            ->get();

        $this->assertTrue($expiredMembers->contains($expiredMember));
        $this->assertFalse($expiredMembers->contains($activeMember));
    }

    /** @test */
    public function database_indexes_exist_for_performance()
    {
        // Check if important indexes exist
        $indexes = DB::select("SHOW INDEX FROM registrations WHERE Key_name != 'PRIMARY'");
        
        $this->assertNotEmpty($indexes, 'No indexes found on registrations table');
    }
}





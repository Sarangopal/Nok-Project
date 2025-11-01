<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Registration;
use App\Models\Renewal;
use App\Models\Event;
use App\Models\Offer;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AutomatedTestSeeder extends Seeder
{
    /**
     * Seed the database for automated testing.
     */
    public function run(): void
    {
        $this->command->info('Starting automated test data seeding...');
        
        // Create admin users (or update if exists)
        User::updateOrCreate(
            ['email' => 'admin@nok-kuwait.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('AdminSecure123!'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'test.admin@nok-kuwait.com'],
            [
                'name' => 'Test Admin',
                'password' => Hash::make('TestAdmin123!'),
            ]
        );

        // Create pending registration (awaiting approval)
        Registration::updateOrCreate(
            ['civil_id' => '10000000001'],
            [
                'memberName' => 'Pending Member',
                'age' => 35,
                'gender' => 'Male',
                'email' => 'pending@test.com',
                'whatsapp' => '+96550000001',
                'renewal_status' => 'pending',
                'blood_group' => 'O+',
                'password' => Hash::make('Test123!'),
            ]
        );

        // Create active member
        $activeMember = Registration::updateOrCreate(
            ['civil_id' => '20000000002'],
            [
                'memberName' => 'Active Member',
                'age' => 40,
                'gender' => 'Male',
                'email' => 'active@test.com',
                'whatsapp' => '+96550000002',
                'renewal_status' => 'approved',
                'card_valid_until' => Carbon::now()->addMonths(18),
                'nok_id' => 'NOK2024001',
                'blood_group' => 'A+',
                'password' => Hash::make('Test123!'),
            ]
        );

        // Create expiring soon member (expires in 5 days)
        Registration::updateOrCreate(
            ['civil_id' => '30000000003'],
            [
                'memberName' => 'Expiring Soon Member',
                'age' => 32,
                'gender' => 'Female',
                'email' => 'expiring@test.com',
                'whatsapp' => '+96550000003',
                'renewal_status' => 'approved',
                'card_valid_until' => Carbon::now()->addDays(5),
                'nok_id' => 'NOK2023001',
                'blood_group' => 'B+',
                'password' => Hash::make('Test123!'),
            ]
        );

        // Create expired member
        $expiredMember = Registration::updateOrCreate(
            ['civil_id' => '40000000004'],
            [
                'memberName' => 'Expired Member',
                'age' => 45,
                'gender' => 'Male',
                'email' => 'expired@test.com',
                'whatsapp' => '+96550000004',
                'renewal_status' => 'approved',
                'card_valid_until' => Carbon::now()->subMonths(2),
                'nok_id' => 'NOK2022001',
                'blood_group' => 'AB+',
                'password' => Hash::make('Test123!'),
            ]
        );

        // Create member with pending renewal request
        Registration::updateOrCreate(
            ['civil_id' => '50000000005'],
            [
                'memberName' => 'Renewal Requested Member',
                'age' => 38,
                'gender' => 'Female',
                'email' => 'renewal.requested@test.com',
                'whatsapp' => '+96550000005',
                'renewal_status' => 'pending',
                'renewal_requested_at' => Carbon::now()->subDays(3),
                'card_valid_until' => Carbon::now()->subMonth(),
                'nok_id' => 'NOK2023002',
                'blood_group' => 'O-',
                'password' => Hash::make('Test123!'),
            ]
        );

        // Create rejected member
        Registration::updateOrCreate(
            ['civil_id' => '60000000006'],
            [
                'memberName' => 'Rejected Member',
                'age' => 29,
                'gender' => 'Male',
                'email' => 'rejected@test.com',
                'whatsapp' => '+96550000006',
                'renewal_status' => 'rejected',
                'blood_group' => 'A-',
                'password' => Hash::make('Test123!'),
            ]
        );

        // Create renewals for active members (skip if exists)
        if (!Renewal::where('registration_id', $activeMember->id)->exists()) {
            Renewal::create([
                'registration_id' => $activeMember->id,
                'renewal_date' => Carbon::now()->subYear(),
                'status' => 'active',
                'previous_expiry' => Carbon::now()->subYear(),
                'new_expiry' => Carbon::now()->addMonths(18),
            ]);
        }

        if (!Renewal::where('registration_id', $expiredMember->id)->exists()) {
            Renewal::create([
                'registration_id' => $expiredMember->id,
                'renewal_date' => Carbon::now()->subYears(2),
                'status' => 'expired',
                'previous_expiry' => Carbon::now()->subYears(2),
                'new_expiry' => Carbon::now()->subMonths(2),
            ]);
        }

        // Create published events
        Event::updateOrCreate(
            ['slug' => 'annual-general-meeting-2024'],
            [
                'title' => 'Annual General Meeting 2024',
                'description' => 'Join us for our Annual General Meeting to discuss important matters.',
                'event_date' => Carbon::now()->addMonth(),
                'location' => 'NOK Kuwait Main Hall',
                'is_published' => true,
            ]
        );

        Event::updateOrCreate(
            ['slug' => 'onam-festival-celebration'],
            [
                'title' => 'Onam Festival Celebration',
                'description' => 'Celebrate Onam with traditional activities and food.',
                'event_date' => Carbon::now()->addMonths(2),
                'location' => 'NOK Kuwait Grounds',
                'is_published' => true,
            ]
        );

        Event::updateOrCreate(
            ['slug' => 'sports-day-2024'],
            [
                'title' => 'Sports Day 2024',
                'description' => 'Annual sports competition for all members.',
                'event_date' => Carbon::now()->addMonths(3),
                'location' => 'Kuwait Sports Stadium',
                'is_published' => true,
            ]
        );

        // Create draft event (not published)
        Event::updateOrCreate(
            ['slug' => 'upcoming-event-draft'],
            [
                'title' => 'Upcoming Event Draft',
                'description' => 'This event is still in planning.',
                'event_date' => Carbon::now()->addMonths(6),
                'location' => 'TBD',
                'is_published' => false,
            ]
        );

        // Create active offers (only if not exists to avoid duplicates)
        if (Offer::where('title', '20% Discount at Restaurant XYZ')->doesntExist()) {
            Offer::create([
                'title' => '20% Discount at Restaurant XYZ',
                'description' => 'Show your NOK membership card for 20% off your bill.',
                'company_name' => 'Restaurant XYZ',
                'valid_from' => Carbon::now()->subMonth(),
                'valid_until' => Carbon::now()->addMonths(6),
                'is_active' => true,
            ]);
        }

        if (Offer::where('title', 'Free Gym Membership Trial')->doesntExist()) {
            Offer::create([
                'title' => 'Free Gym Membership Trial',
                'description' => 'Get 1 month free trial at FitLife Gym.',
                'company_name' => 'FitLife Gym',
                'valid_from' => Carbon::now()->subWeek(),
                'valid_until' => Carbon::now()->addMonths(3),
                'is_active' => true,
            ]);
        }

        if (Offer::where('title', '15% Off Electronics')->doesntExist()) {
            Offer::create([
                'title' => '15% Off Electronics',
                'description' => 'Discount on all electronics at TechStore.',
                'company_name' => 'TechStore Kuwait',
                'valid_from' => Carbon::now(),
                'valid_until' => Carbon::now()->addMonths(2),
                'is_active' => true,
            ]);
        }

        // Create expired offer
        if (Offer::where('title', 'Expired Summer Offer')->doesntExist()) {
            Offer::create([
                'title' => 'Expired Summer Offer',
                'description' => 'This offer has expired.',
                'company_name' => 'Summer Store',
                'valid_from' => Carbon::now()->subMonths(6),
                'valid_until' => Carbon::now()->subMonth(),
                'is_active' => false,
            ]);
        }

        // Create inactive offer
        if (Offer::where('title', 'Inactive Winter Offer')->doesntExist()) {
            Offer::create([
                'title' => 'Inactive Winter Offer',
                'description' => 'This offer is currently inactive.',
                'company_name' => 'Winter Store',
                'valid_from' => Carbon::now()->addMonth(),
                'valid_until' => Carbon::now()->addMonths(4),
                'is_active' => false,
            ]);
        }

        // Create contact messages (only if not exists)
        if (ContactMessage::where('email', 'john.doe@example.com')->doesntExist()) {
            ContactMessage::create([
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+96512345678',
                'message' => 'I would like to inquire about membership.',
                'created_at' => Carbon::now()->subDays(5),
            ]);
        }

        if (ContactMessage::where('email', 'jane.smith@example.com')->doesntExist()) {
            ContactMessage::create([
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'phone' => '+96587654321',
                'message' => 'How can I renew my membership?',
                'created_at' => Carbon::now()->subDays(3),
            ]);
        }

        if (ContactMessage::where('email', 'ahmed@example.com')->doesntExist()) {
            ContactMessage::create([
                'name' => 'Ahmed Al-Ibrahim',
                'email' => 'ahmed@example.com',
                'phone' => '+96511111111',
                'message' => 'Question about upcoming events.',
                'created_at' => Carbon::now()->subDay(),
            ]);
        }

        $this->command->info('✓ Automated test data seeded successfully!');
        $this->command->info('');
        $this->command->info('Test Users Created:');
        $this->command->info('  Admin: admin@nok-kuwait.com / AdminSecure123!');
        $this->command->info('  Test Admin: test.admin@nok-kuwait.com / TestAdmin123!');
        $this->command->info('');
        $this->command->info('Test Members Created:');
        $this->command->info('  Active: 20000000002 / Test123!');
        $this->command->info('  Expired: 40000000004 / Test123!');
        $this->command->info('  Expiring Soon: 30000000003 / Test123!');
        $this->command->info('  Pending: 10000000001 / Test123!');
        $this->command->info('');
        $this->command->info('Other Data: 4 Events, 5 Offers, 3 Contact Messages');
    }
}


<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Registration;

class TestDataSeeder extends Seeder
{
    /**
     * Output helper that works with or without command
     */
    private function output($message)
    {
        if ($this->command) {
            $this->command->info($message);
        } else {
            echo $message . "\n";
        }
    }

    private function line($message)
    {
        if ($this->command) {
            $this->command->line($message);
        } else {
            echo $message . "\n";
        }
    }

    private function newLine()
    {
        if ($this->command) {
            $this->command->newLine();
        } else {
            echo "\n";
        }
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->output('🌱 Seeding test data...');
        $this->output('⚠️  This will DELETE all existing registrations!');
        $this->newLine();

        // Clear existing test data (delete instead of truncate due to foreign keys)
        $count = Registration::count();
        if ($count > 0) {
            \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Registration::truncate();
            \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->output("✅ Cleared $count existing registrations");
        } else {
            $this->output('ℹ️  No existing registrations to clear');
        }
        $this->newLine();

        // 1. Create PENDING registrations (need admin approval)
        $this->output('Creating pending registrations...');
        
        Registration::create([
            'memberName' => 'Sarah Johnson',
            'age' => 32,
            'gender' => 'Female',
            'email' => 'sarah.johnson@example.com',
            'mobile' => '+96512345678',
            'whatsapp' => '+96512345678',
            'department' => 'ICU',
            'job_title' => 'Staff Nurse',
            'institution' => 'Kuwait Hospital',
            'passport' => 'P1234567',
            'civil_id' => '287654321012345',
            'blood_group' => 'O+',
            'phone_india' => '+919876543210',
            'nominee_name' => 'John Johnson',
            'nominee_relation' => 'Spouse',
            'nominee_contact' => '+96512345679',
            'login_status' => 'pending',
            'doj' => now()->subMonths(1),
        ]);

        Registration::create([
            'memberName' => 'Michael Smith',
            'age' => 28,
            'gender' => 'Male',
            'email' => 'michael.smith@example.com',
            'mobile' => '+96512345680',
            'whatsapp' => '+96512345680',
            'department' => 'Emergency',
            'job_title' => 'Registered Nurse',
            'institution' => 'Al Amiri Hospital',
            'passport' => 'P2345678',
            'civil_id' => '287654321012346',
            'blood_group' => 'A+',
            'phone_india' => '+919876543211',
            'nominee_name' => 'Emily Smith',
            'nominee_relation' => 'Sister',
            'nominee_contact' => '+96512345681',
            'login_status' => 'pending',
            'doj' => now()->subDays(15),
        ]);

        Registration::create([
            'memberName' => 'Priya Sharma',
            'age' => 35,
            'gender' => 'Female',
            'email' => 'priya.sharma@example.com',
            'mobile' => '+96512345682',
            'whatsapp' => '+96512345682',
            'department' => 'Pediatrics',
            'job_title' => 'Senior Nurse',
            'institution' => 'Mubarak Hospital',
            'passport' => 'P3456789',
            'civil_id' => '287654321012347',
            'blood_group' => 'B+',
            'phone_india' => '+919876543212',
            'nominee_name' => 'Raj Sharma',
            'nominee_relation' => 'Spouse',
            'nominee_contact' => '+96512345683',
            'login_status' => 'pending',
            'doj' => now()->subDays(5),
        ]);

        // 2. Create APPROVED members (with NOK IDs and passwords)
        $this->output('Creating approved members...');

        $approvedMembers = [];

        $approvedMembers[] = Registration::create([
            'memberName' => 'Aisha Mohammed',
            'nok_id' => 'NOK001001',
            'age' => 30,
            'gender' => 'Female',
            'email' => 'aisha.mohammed@example.com',
            'mobile' => '+96512345690',
            'whatsapp' => '+96512345690',
            'department' => 'Surgery',
            'job_title' => 'Registered Nurse',
            'institution' => 'Farwaniya Hospital',
            'passport' => 'P4567890',
            'civil_id' => '287654321012348',
            'blood_group' => 'O-',
            'phone_india' => '+919876543213',
            'nominee_name' => 'Fatima Mohammed',
            'nominee_relation' => 'Mother',
            'nominee_contact' => '+96512345691',
            'login_status' => 'approved',
            'password' => bcrypt('NOK1234'), // Password: NOK1234
            'doj' => now()->subMonths(6),
            'card_issued_at' => now()->subMonths(6),
            'card_valid_until' => now()->endOfYear(),
            'renewal_count' => 0,
        ]);

        $approvedMembers[] = Registration::create([
            'memberName' => 'John Doe',
            'nok_id' => 'NOK001002',
            'age' => 29,
            'gender' => 'Male',
            'email' => 'john.doe@example.com',
            'mobile' => '+96512345692',
            'whatsapp' => '+96512345692',
            'department' => 'Cardiology',
            'job_title' => 'Staff Nurse',
            'institution' => 'Chest Hospital',
            'passport' => 'P5678901',
            'civil_id' => '287654321012349',
            'blood_group' => 'AB+',
            'phone_india' => '+919876543214',
            'nominee_name' => 'Jane Doe',
            'nominee_relation' => 'Wife',
            'nominee_contact' => '+96512345693',
            'login_status' => 'approved',
            'password' => bcrypt('NOK5678'), // Password: NOK5678
            'doj' => now()->subMonths(8),
            'card_issued_at' => now()->subMonths(8),
            'card_valid_until' => now()->endOfYear(),
            'renewal_count' => 0,
        ]);

        $approvedMembers[] = Registration::create([
            'memberName' => 'Maria Garcia',
            'nok_id' => 'NOK001003',
            'age' => 33,
            'gender' => 'Female',
            'email' => 'maria.garcia@example.com',
            'mobile' => '+96512345694',
            'whatsapp' => '+96512345694',
            'department' => 'Oncology',
            'job_title' => 'Senior Nurse',
            'institution' => 'Kuwait Cancer Center',
            'passport' => 'P6789012',
            'civil_id' => '287654321012350',
            'blood_group' => 'A-',
            'phone_india' => '+919876543215',
            'nominee_name' => 'Carlos Garcia',
            'nominee_relation' => 'Brother',
            'nominee_contact' => '+96512345695',
            'login_status' => 'approved',
            'password' => bcrypt('NOK9012'), // Password: NOK9012
            'doj' => now()->subYear(),
            'card_issued_at' => now()->subYear(),
            'card_valid_until' => now()->endOfYear(),
            'renewal_count' => 1,
            'last_renewed_at' => now()->subMonths(3),
        ]);

        // Member with EXPIRED membership
        $approvedMembers[] = Registration::create([
            'memberName' => 'David Brown',
            'nok_id' => 'NOK001004',
            'age' => 31,
            'gender' => 'Male',
            'email' => 'david.brown@example.com',
            'mobile' => '+96512345696',
            'whatsapp' => '+96512345696',
            'department' => 'Orthopedics',
            'job_title' => 'Registered Nurse',
            'institution' => 'Razi Hospital',
            'passport' => 'P7890123',
            'civil_id' => '287654321012351',
            'blood_group' => 'B-',
            'phone_india' => '+919876543216',
            'nominee_name' => 'Sarah Brown',
            'nominee_relation' => 'Sister',
            'nominee_contact' => '+96512345697',
            'login_status' => 'approved',
            'password' => bcrypt('NOK3456'), // Password: NOK3456
            'doj' => now()->subYears(2),
            'card_issued_at' => now()->subYears(2),
            'card_valid_until' => now()->subMonths(2), // EXPIRED 2 months ago
            'renewal_count' => 1,
            'last_renewed_at' => now()->subYear(),
        ]);

        // 3. Create REJECTED registration
        $this->output('Creating rejected registration...');

        Registration::create([
            'memberName' => 'Test Rejected',
            'age' => 25,
            'gender' => 'Male',
            'email' => 'rejected@example.com',
            'mobile' => '+96512345698',
            'whatsapp' => '+96512345698',
            'department' => 'General',
            'job_title' => 'Nurse',
            'institution' => 'Test Hospital',
            'passport' => 'P8901234',
            'civil_id' => '287654321012352',
            'blood_group' => 'O+',
            'login_status' => 'rejected',
            'doj' => now()->subDays(10),
        ]);

        // 4. Create members with RENEWAL STATUS for testing renewals
        $this->output('Creating members with renewal status...');

        // Member with pending renewal request
        Registration::create([
            'memberName' => 'Lisa Wong',
            'nok_id' => 'NOK001005',
            'age' => 34,
            'gender' => 'Female',
            'email' => 'lisa.wong@example.com',
            'mobile' => '+96512345699',
            'whatsapp' => '+96512345699',
            'department' => 'Nephrology',
            'job_title' => 'Registered Nurse',
            'institution' => 'Jahra Hospital',
            'passport' => 'P9012345',
            'civil_id' => '287654321012353',
            'blood_group' => 'O+',
            'phone_india' => '+919876543217',
            'nominee_name' => 'Michael Wong',
            'nominee_relation' => 'Husband',
            'nominee_contact' => '+96512345700',
            'login_status' => 'approved',
            'renewal_status' => 'pending',
            'password' => bcrypt('NOK7890'), // Password: NOK7890
            'doj' => now()->subYear(),
            'card_issued_at' => now()->subYear(),
            'card_valid_until' => now()->addDays(20), // Expiring soon
            'renewal_count' => 0,
        ]);

        // Member with approved renewal
        Registration::create([
            'memberName' => 'Ahmed Hassan',
            'nok_id' => 'NOK001006',
            'age' => 36,
            'gender' => 'Male',
            'email' => 'ahmed.hassan@example.com',
            'mobile' => '+96512345701',
            'whatsapp' => '+96512345701',
            'department' => 'Radiology',
            'job_title' => 'Senior Nurse',
            'institution' => 'Sabah Hospital',
            'passport' => 'P0123456',
            'civil_id' => '287654321012354',
            'blood_group' => 'AB-',
            'phone_india' => '+919876543218',
            'nominee_name' => 'Fatima Hassan',
            'nominee_relation' => 'Sister',
            'nominee_contact' => '+96512345702',
            'login_status' => 'approved',
            'renewal_status' => 'approved',
            'password' => bcrypt('NOK1122'), // Password: NOK1122
            'doj' => now()->subYears(2),
            'card_issued_at' => now()->subYears(2),
            'card_valid_until' => now()->addYear(), // Recently renewed
            'renewal_count' => 2,
            'last_renewed_at' => now()->subMonths(1),
        ]);

        $this->output('✅ Test data seeded successfully!');
        $this->newLine();
        
        // Display credentials
        $this->output('📋 TEST CREDENTIALS:');
        $this->newLine();
        
        $this->output('🔐 ADMIN LOGIN:');
        $this->line('   URL: http://127.0.0.1:8000/admin/login');
        $this->line('   Email: admin@gmail.com');
        $this->line('   Password: secret');
        $this->newLine();
        
        $this->output('👤 MEMBER LOGINS (use Civil ID + Password):');
        $this->line('   1. Aisha Mohammed');
        $this->line('      Civil ID: 287654321012348');
        $this->line('      Password: NOK1234');
        $this->line('      Status: Active, Has pending renewal request');
        $this->newLine();
        
        $this->line('   2. John Doe');
        $this->line('      Civil ID: 287654321012349');
        $this->line('      Password: NOK5678');
        $this->line('      Status: Active, Approved renewal');
        $this->newLine();
        
        $this->line('   3. Maria Garcia');
        $this->line('      Civil ID: 287654321012350');
        $this->line('      Password: NOK9012');
        $this->line('      Status: Active, Already renewed once');
        $this->newLine();
        
        $this->line('   4. David Brown');
        $this->line('      Civil ID: 287654321012351');
        $this->line('      Password: NOK3456');
        $this->line('      Status: EXPIRED (for renewal testing)');
        $this->newLine();
        
        $this->line('   5. Lisa Wong');
        $this->line('      Civil ID: 287654321012353');
        $this->line('      Password: NOK7890');
        $this->line('      Status: Active with pending renewal request');
        $this->newLine();
        
        $this->line('   6. Ahmed Hassan');
        $this->line('      Civil ID: 287654321012354');
        $this->line('      Password: NOK1122');
        $this->line('      Status: Active with approved renewal');
        $this->newLine();
        
        $this->output('📊 DATA SUMMARY:');
        $this->line('   - 3 Pending registrations (need approval)');
        $this->line('   - 6 Approved members (can login)');
        $this->line('   - 1 Rejected registration');
        $this->line('   - 1 Expired member');
        $this->line('   - 2 Members with renewal status (1 pending, 1 approved)');
        $this->newLine();
        
        $this->output('🧪 READY FOR TESTING!');
    }
}

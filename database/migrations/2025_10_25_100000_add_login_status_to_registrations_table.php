<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            // Add login_status for new registration approval
            $table->enum('login_status', ['pending', 'approved', 'rejected'])
                ->default('pending')
                ->after('renewal_status')
                ->comment('Login/Registration approval status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn('login_status');
        });
    }
};


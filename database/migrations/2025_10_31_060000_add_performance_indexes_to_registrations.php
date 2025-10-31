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
            // Add indexes to frequently queried fields for duplicate checking
            $table->index('mobile', 'idx_registrations_mobile');
            $table->index('passport', 'idx_registrations_passport');
            $table->index('civil_id', 'idx_registrations_civil_id');
            
            // Optional: Add unique constraints if these should be unique
            // Uncomment if you want to enforce uniqueness at database level
            // $table->unique('mobile');
            // $table->unique('passport');
            // $table->unique('civil_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropIndex('idx_registrations_mobile');
            $table->dropIndex('idx_registrations_passport');
            $table->dropIndex('idx_registrations_civil_id');
        });
    }
};


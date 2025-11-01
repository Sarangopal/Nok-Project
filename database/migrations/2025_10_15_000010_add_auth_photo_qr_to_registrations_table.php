<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('registrations', 'password')) {
                $table->string('password')->nullable()->after('email');
            }
            if (!Schema::hasColumn('registrations', 'photo_path')) {
                $table->string('photo_path')->nullable()->after('password');
            }
            if (!Schema::hasColumn('registrations', 'qr_code_path')) {
                $table->string('qr_code_path')->nullable()->after('photo_path');
            }
            if (!Schema::hasColumn('registrations', 'renewal_date')) {
                $table->date('renewal_date')->nullable()->after('card_valid_until');
            }
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $drops = [];
            foreach (['password','photo_path','qr_code_path','renewal_date'] as $col) {
                if (Schema::hasColumn('registrations', $col)) {
                    $drops[] = $col;
                }
            }
            if (!empty($drops)) {
                $table->dropColumn($drops);
            }
        });
    }
};



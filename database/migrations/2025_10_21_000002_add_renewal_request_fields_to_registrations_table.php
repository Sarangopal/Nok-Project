<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('registrations', 'renewal_requested_at')) {
                $table->timestamp('renewal_requested_at')->nullable()->after('renewal_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            if (Schema::hasColumn('registrations', 'renewal_requested_at')) {
                $table->dropColumn('renewal_requested_at');
            }
        });
    }
};



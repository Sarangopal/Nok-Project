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
            $table->timestamp('card_issued_at')->nullable()->after('renewal_status');
            $table->timestamp('last_renewed_at')->nullable()->after('card_issued_at');
            $table->integer('renewal_count')->default(0)->after('last_renewed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn(['card_issued_at', 'last_renewed_at', 'renewal_count']);
        });
    }
};

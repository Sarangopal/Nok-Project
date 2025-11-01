<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('verification_attempts', function (Blueprint $table) {
            $table->index(['was_successful']);
        });
    }

    public function down(): void
    {
        Schema::table('verification_attempts', function (Blueprint $table) {
            $table->dropIndex(['was_successful']);
        });
    }
};




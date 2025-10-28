<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_offer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registration_id');
            $table->unsignedBigInteger('offer_id');
            $table->timestamps();

            $table->unique(['registration_id', 'offer_id']);
            $table->foreign('registration_id')->references('id')->on('registrations')->onDelete('cascade');
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_offer');
    }
};



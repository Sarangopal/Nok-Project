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
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image'); // Path to uploaded image
            $table->string('category')->default('general'); // e.g., 'aaravam', 'nightingales2024', 'others'
            $table->year('year')->default(date('Y')); // Year of the event
            $table->integer('display_order')->default(0); // For custom ordering
            $table->boolean('is_published')->default(true); // Show/hide images
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};





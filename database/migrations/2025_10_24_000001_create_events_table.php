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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable(); // Short description for listing
            $table->longText('body'); // Full content (WYSIWYG)
            $table->date('event_date');
            $table->string('event_time')->nullable();
            $table->string('location')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('category')->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('featured')->default(false);
            $table->string('meta_description')->nullable();
            $table->timestamps();
            
            $table->index('slug');
            $table->index('event_date');
            $table->index('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};


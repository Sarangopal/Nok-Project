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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            // $table->string('member_type')->nullable();
            $table->enum('member_type', ['new', 'existing'])->default('new');
            $table->string('nok_id')->nullable();
            $table->date('doj')->nullable();
            $table->string('memberName');
            $table->integer('age');
            $table->string('gender');
            $table->string('email')->unique();
            $table->string('mobile');
            $table->string('whatsapp')->nullable();
            $table->string('department')->nullable();
            $table->string('job_title')->nullable();
            $table->string('institution')->nullable();
            $table->string('passport')->nullable();
            $table->string('civil_id')->nullable();
            $table->string('blood_group')->nullable();
            $table->text('address')->nullable();
            $table->string('phone_india')->nullable();
            $table->string('nominee_name')->nullable();
            $table->string('nominee_relation')->nullable();
            $table->string('nominee_contact')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_contact')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('bank_branch')->nullable();
            $table->enum('renewal_status', ['pending', 'approved'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};

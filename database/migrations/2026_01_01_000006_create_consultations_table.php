<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->string('consultation_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('institution')->nullable();
            $table->string('consultation_type')->default('Personal RQ Consultation'); // Personal, Institution, Research, Executive
            $table->date('preferred_date');
            $table->string('preferred_time')->default('10:00 - 11:30');
            $table->text('message');
            $table->string('status')->default('new'); // new, contacted, scheduled, completed, cancelled
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};

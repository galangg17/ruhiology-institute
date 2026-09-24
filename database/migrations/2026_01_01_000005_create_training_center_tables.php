<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description');
            $table->string('category')->default('Sertifikasi');
            $table->string('image')->nullable();
            $table->string('trainer')->default('Prof. Dr. Iskandar Nazari & Tim');
            $table->string('duration')->default('2 Hari');
            $table->string('location')->default('Online (Zoom)');
            $table->boolean('is_online')->default(true);
            $table->decimal('price', 12, 2)->default(0);
            $table->integer('quota')->default(30);
            $table->string('status')->default('published'); // draft, published, archived
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('training_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained('training_programs')->onDelete('cascade');
            $table->string('batch_name');
            $table->date('start_date');
            $table->date('end_date');
            $table->dateTime('registration_open');
            $table->dateTime('registration_close');
            $table->integer('quota')->default(30);
            $table->string('status')->default('open'); // open, closed, completed
            $table->timestamps();
        });

        Schema::create('training_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->foreignId('training_id')->constrained('training_programs')->onDelete('cascade');
            $table->foreignId('batch_id')->constrained('training_batches')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('participant_name');
            $table->string('email');
            $table->string('phone');
            $table->string('institution')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->string('payment_status')->default('pending'); // pending, verified, rejected
            $table->string('registration_status')->default('pending'); // pending, approved, rejected, completed, cancelled
            $table->string('payment_proof_path')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_registrations');
        Schema::dropIfExists('training_batches');
        Schema::dropIfExists('training_programs');
    }
};

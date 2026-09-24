<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name');
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['country_id', 'status']);
        });

        Schema::create('regencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id')->constrained('provinces')->onDelete('cascade');
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->string('type')->default('Kabupaten'); // Kabupaten / Kota
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['province_id', 'status']);
        });

        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id')->constrained('provinces')->onDelete('cascade');
            $table->foreignId('regency_id')->constrained('regencies')->onDelete('cascade');
            $table->string('level', 20); // SD, SMP, SMA, SMK, Sederajat
            $table->string('npsn', 30)->nullable();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['province_id', 'regency_id', 'level', 'status']);
        });

        Schema::create('universities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id')->constrained('provinces')->onDelete('cascade');
            $table->foreignId('regency_id')->constrained('regencies')->onDelete('cascade');
            $table->string('code', 30)->nullable();
            $table->string('name');
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['province_id', 'regency_id', 'status']);
        });

        Schema::create('faculties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->onDelete('cascade');
            $table->string('code', 30)->nullable();
            $table->string('name');
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['university_id', 'status']);
        });

        Schema::create('study_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('university_id')->constrained('universities')->onDelete('cascade');
            $table->foreignId('faculty_id')->constrained('faculties')->onDelete('cascade');
            $table->string('code', 30)->nullable();
            $table->string('name');
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['university_id', 'faculty_id', 'status']);
        });

        Schema::create('occupations', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->nullable();
            $table->string('name');
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pending_institutions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->default('Umum'); // Pelajar, Mahasiswa, Umum
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('pending'); // pending, verified, rejected
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pending_institutions');
        Schema::dropIfExists('occupations');
        Schema::dropIfExists('study_programs');
        Schema::dropIfExists('faculties');
        Schema::dropIfExists('universities');
        Schema::dropIfExists('schools');
        Schema::dropIfExists('regencies');
        Schema::dropIfExists('provinces');
        Schema::dropIfExists('countries');
    }
};

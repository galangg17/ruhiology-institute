<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create dedicated Events table
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('event_code', 50)->unique();
            $table->string('title');
            $table->string('access_type', 30)->default('EVENT_PROGRAM'); // EVENT_PROGRAM, PUBLIC_SELF
            $table->string('institution_name')->nullable();
            $table->foreignId('program_id')->nullable()->constrained('programs')->nullOnDelete();
            $table->foreignId('instrument_id')->nullable()->constrained('instruments')->nullOnDelete();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->integer('quota')->nullable();
            $table->string('status', 20)->default('active'); // draft, active, completed, archived
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 2. Add event_id and access_type to participants
        Schema::table('participants', function (Blueprint $table) {
            $table->foreignId('event_id')->nullable()->after('id')->constrained('events')->nullOnDelete();
            $table->string('access_type', 30)->default('PUBLIC_SELF')->after('event_id');
        });

        // 3. Add event_id and access_type to assessment_submissions
        Schema::table('assessment_submissions', function (Blueprint $table) {
            $table->foreignId('event_id')->nullable()->after('period_id')->constrained('events')->nullOnDelete();
            $table->string('access_type', 30)->default('PUBLIC_SELF')->after('event_id');
        });

        // 4. Add dimension_scores JSON column to assessment_results for RQI-15 breakdown
        Schema::table('assessment_results', function (Blueprint $table) {
            $table->json('dimension_scores')->nullable()->after('rqi_score');
        });
    }

    public function down(): void
    {
        Schema::table('assessment_results', function (Blueprint $table) {
            $table->dropColumn('dimension_scores');
        });

        Schema::table('assessment_submissions', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn(['event_id', 'access_type']);
        });

        Schema::table('participants', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn(['event_id', 'access_type']);
        });

        Schema::dropIfExists('events');
    }
};

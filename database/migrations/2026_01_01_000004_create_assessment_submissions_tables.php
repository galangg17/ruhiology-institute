<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('programs')->onDelete('cascade');
            $table->foreignId('instrument_id')->constrained('instruments')->onDelete('cascade');
            $table->string('title');
            $table->string('period_code')->unique();
            $table->dateTime('pretest_start')->nullable();
            $table->dateTime('pretest_end')->nullable();
            $table->dateTime('posttest_start')->nullable();
            $table->dateTime('posttest_end')->nullable();
            $table->string('status')->default('active'); // draft, active, closed, archived
            $table->timestamps();
        });

        Schema::create('assessment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_id')->constrained('assessment_periods')->onDelete('cascade');
            $table->foreignId('participant_id')->constrained('participants')->onDelete('cascade');
            $table->string('submission_type'); // pretest, posttest
            $table->string('submission_code')->unique();
            $table->string('status')->default('in_progress'); // in_progress, submitted
            $table->dateTime('started_at');
            $table->dateTime('submitted_at')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();

            // Strict Anti-Duplicate constraint: A participant can only have 1 pretest and 1 posttest per period
            $table->unique(['period_id', 'participant_id', 'submission_type'], 'uniq_period_participant_type');
        });

        Schema::create('assessment_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('assessment_submissions')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->foreignId('question_option_id')->nullable()->constrained('question_options')->nullOnDelete();
            $table->integer('raw_value');
            $table->decimal('calculated_score', 8, 2);
            $table->timestamps();
        });

        Schema::create('assessment_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('assessment_submissions')->onDelete('cascade');
            $table->decimal('total_score', 8, 2);
            $table->decimal('max_score', 8, 2);
            $table->decimal('percentage', 5, 2);
            $table->text('overall_interpretation')->nullable();
            $table->decimal('pre_post_diff', 8, 2)->nullable(); // For posttest, compared to pretest score
            $table->timestamps();
        });

        Schema::create('result_dimensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('result_id')->constrained('assessment_results')->onDelete('cascade');
            $table->foreignId('dimension_id')->constrained('dimensions')->onDelete('cascade');
            $table->decimal('score', 8, 2);
            $table->decimal('max_score', 8, 2);
            $table->decimal('percentage', 5, 2);
            $table->text('interpretation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('result_dimensions');
        Schema::dropIfExists('assessment_results');
        Schema::dropIfExists('assessment_answers');
        Schema::dropIfExists('assessment_submissions');
        Schema::dropIfExists('assessment_periods');
    }
};

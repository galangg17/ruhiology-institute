<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instruments', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('version')->default('1.0');
            $table->text('instructions')->nullable();
            $table->string('status')->default('active'); // draft, active, archived
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('dimensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instrument_id')->constrained('instruments')->onDelete('cascade');
            $table->string('code');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('order')->default(1);
            $table->timestamps();
        });

        Schema::create('indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dimension_id')->constrained('dimensions')->onDelete('cascade');
            $table->string('code');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('order')->default(1);
            $table->timestamps();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instrument_id')->constrained('instruments')->onDelete('cascade');
            $table->foreignId('dimension_id')->constrained('dimensions')->onDelete('cascade');
            $table->foreignId('indicator_id')->nullable()->constrained('indicators')->nullOnDelete();
            $table->text('question_text');
            $table->string('type')->default('likert'); // likert, multiple_choice, yes_no
            $table->string('scoring_direction')->default('normal'); // normal, reverse
            $table->integer('order')->default(1);
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();
        });

        Schema::create('question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->string('option_text');
            $table->integer('option_value'); // e.g. 1 to 5
            $table->integer('order')->default(1);
            $table->timestamps();
        });

        Schema::create('scoring_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instrument_id')->constrained('instruments')->onDelete('cascade');
            $table->integer('scale_min')->default(1);
            $table->integer('scale_max')->default(5);
            $table->json('reverse_mapping')->nullable(); // e.g. {"1": 5, "2": 4, "3": 3, "4": 2, "5": 1}
            $table->json('interpretation_ranges')->nullable(); // e.g. [{"min": 0, "max": 40, "level": "Low", "description": "..."}, ...]
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scoring_rules');
        Schema::dropIfExists('question_options');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('indicators');
        Schema::dropIfExists('dimensions');
        Schema::dropIfExists('instruments');
    }
};

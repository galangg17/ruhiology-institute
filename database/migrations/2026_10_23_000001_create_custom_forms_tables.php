<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_forms', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('header_image')->nullable();
            $table->enum('status', ['draft', 'published', 'closed'])->default('published');
            $table->boolean('is_public')->default(true);
            $table->string('access_code', 50)->nullable();
            $table->dateTime('closes_at')->nullable();
            $table->text('success_message')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('custom_form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_form_id')->constrained('custom_forms')->cascadeOnDelete();
            $table->string('label');
            $table->text('description')->nullable();
            $table->string('field_type', 30)->default('text'); // text, textarea, number, radio, checkbox, dropdown, date, file
            $table->json('options')->nullable(); // array of strings for radio/checkbox/dropdown
            $table->boolean('is_required')->default(false);
            $table->string('placeholder')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('custom_form_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_form_id')->constrained('custom_forms')->cascadeOnDelete();
            $table->string('response_code', 30)->unique();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->dateTime('submitted_at')->useCurrent();
            $table->timestamps();
        });

        Schema::create('custom_form_response_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('response_id')->constrained('custom_form_responses')->cascadeOnDelete();
            $table->foreignId('field_id')->constrained('custom_form_fields')->cascadeOnDelete();
            $table->longText('answer_value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_form_response_answers');
        Schema::dropIfExists('custom_form_responses');
        Schema::dropIfExists('custom_form_fields');
        Schema::dropIfExists('custom_forms');
    }
};

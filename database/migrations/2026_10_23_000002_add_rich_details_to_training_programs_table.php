<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_programs', function (Blueprint $table) {
            $table->longText('syllabus')->nullable()->after('description');
            $table->longText('facilities')->nullable()->after('syllabus');
            $table->text('target_audience')->nullable()->after('facilities');
            $table->text('trainer_bio')->nullable()->after('trainer');
            $table->string('contact_person')->nullable()->after('location');
        });
    }

    public function down(): void
    {
        Schema::table('training_programs', function (Blueprint $table) {
            $table->dropColumn(['syllabus', 'facilities', 'target_audience', 'trainer_bio', 'contact_person']);
        });
    }
};

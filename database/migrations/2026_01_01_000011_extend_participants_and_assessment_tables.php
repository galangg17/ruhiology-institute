<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->date('birth_date')->nullable()->after('name');
            $table->string('category')->default('Umum')->after('birth_date'); // Pelajar, Mahasiswa/i, Umum
            
            $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete()->after('category');
            $table->foreignId('province_id')->nullable()->constrained('provinces')->nullOnDelete()->after('country_id');
            $table->foreignId('regency_id')->nullable()->constrained('regencies')->nullOnDelete()->after('province_id');
            $table->foreignId('school_id')->nullable()->constrained('schools')->nullOnDelete()->after('regency_id');
            $table->foreignId('university_id')->nullable()->constrained('universities')->nullOnDelete()->after('school_id');
            $table->foreignId('faculty_id')->nullable()->constrained('faculties')->nullOnDelete()->after('university_id');
            $table->foreignId('study_program_id')->nullable()->constrained('study_programs')->nullOnDelete()->after('faculty_id');
            
            $table->string('school_level', 20)->nullable()->after('study_program_id');
            $table->string('school_class', 20)->nullable()->after('school_level');
            $table->integer('semester')->nullable()->after('school_class');
            $table->string('entry_year', 10)->nullable()->after('semester');
            
            $table->foreignId('occupation_id')->nullable()->constrained('occupations')->nullOnDelete()->after('entry_year');
            $table->string('occupation_custom')->nullable()->after('occupation_id');
            
            $table->string('assessment_code', 30)->nullable()->unique()->after('participant_code');

            $table->index(['category', 'province_id', 'regency_id'], 'idx_part_cat_prov_reg');
            $table->index(['university_id', 'faculty_id', 'study_program_id'], 'idx_part_univ_fac_prog');
        });

        Schema::table('assessment_submissions', function (Blueprint $table) {
            $table->string('cycle_status')->default('PRE_COMPLETED')->after('status'); // CREATED, PRE_AVAILABLE, PRE_COMPLETED, POST_LOCKED, POST_AVAILABLE, POST_COMPLETED
            $table->index(['participant_id', 'submission_type', 'cycle_status'], 'idx_sub_part_type_cycle');
        });

        Schema::table('assessment_results', function (Blueprint $table) {
            $table->decimal('rqi_score', 8, 2)->nullable()->after('total_score');
            $table->string('category_name')->nullable()->after('rqi_score');
            $table->integer('who5_raw_score')->nullable()->after('category_name');
            $table->decimal('who5_percentage', 5, 2)->nullable()->after('who5_raw_score');
            $table->text('who5_screening_note')->nullable()->after('who5_percentage');
            $table->text('reflection_text')->nullable()->after('who5_screening_note');
            $table->string('scoring_version', 20)->default('1.0')->after('reflection_text');
            $table->json('snapshot_data')->nullable()->after('scoring_version');
        });
    }

    public function down(): void
    {
        Schema::table('assessment_results', function (Blueprint $table) {
            $table->dropColumn([
                'rqi_score',
                'category_name',
                'who5_raw_score',
                'who5_percentage',
                'who5_screening_note',
                'reflection_text',
                'scoring_version',
                'snapshot_data',
            ]);
        });

        Schema::table('assessment_submissions', function (Blueprint $table) {
            $table->dropIndex('idx_sub_part_type_cycle');
            $table->dropColumn('cycle_status');
        });

        Schema::table('participants', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropForeign(['province_id']);
            $table->dropForeign(['regency_id']);
            $table->dropForeign(['school_id']);
            $table->dropForeign(['university_id']);
            $table->dropForeign(['faculty_id']);
            $table->dropForeign(['study_program_id']);
            $table->dropForeign(['occupation_id']);

            $table->dropColumn([
                'birth_date',
                'category',
                'country_id',
                'province_id',
                'regency_id',
                'school_id',
                'university_id',
                'faculty_id',
                'study_program_id',
                'school_level',
                'school_class',
                'semester',
                'entry_year',
                'occupation_id',
                'occupation_custom',
                'assessment_code',
            ]);
        });
    }
};

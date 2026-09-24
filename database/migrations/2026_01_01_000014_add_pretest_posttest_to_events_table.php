<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('assessment_type', 30)->default('single')->after('access_type'); // single, prepost
            $table->dateTime('pretest_start')->nullable()->after('end_date');
            $table->dateTime('pretest_end')->nullable()->after('pretest_start');
            $table->dateTime('posttest_start')->nullable()->after('pretest_end');
            $table->dateTime('posttest_end')->nullable()->after('posttest_start');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'assessment_type',
                'pretest_start',
                'pretest_end',
                'posttest_start',
                'posttest_end',
            ]);
        });
    }
};

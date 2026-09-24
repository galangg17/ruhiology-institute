<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('group_label')->nullable()->after('target_category'); // e.g. "Pilih Kelas", "Pilih Divisi/Bidang"
            $table->json('custom_subcategories')->nullable()->after('group_label');
        });

        Schema::table('participants', function (Blueprint $table) {
            $table->string('sub_category')->nullable()->after('category');
        });

        Schema::table('assessment_submissions', function (Blueprint $table) {
            $table->string('sub_category')->nullable()->after('access_type');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['group_label', 'custom_subcategories']);
        });

        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn('sub_category');
        });

        Schema::table('assessment_submissions', function (Blueprint $table) {
            $table->dropColumn('sub_category');
        });
    }
};

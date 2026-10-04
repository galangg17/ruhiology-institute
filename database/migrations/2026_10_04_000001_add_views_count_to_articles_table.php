<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('articles', 'views_count')) {
            Schema::table('articles', function (Blueprint $table) {
                $table->unsignedBigInteger('views_count')->default(0)->after('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('articles', 'views_count')) {
            Schema::table('articles', function (Blueprint $table) {
                $table->dropColumn('views_count');
            });
        }
    }
};

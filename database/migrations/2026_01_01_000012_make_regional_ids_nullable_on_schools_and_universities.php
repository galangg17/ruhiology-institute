<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->foreignId('province_id')->nullable()->change();
            $table->foreignId('regency_id')->nullable()->change();
        });

        Schema::table('universities', function (Blueprint $table) {
            $table->foreignId('province_id')->nullable()->change();
            $table->foreignId('regency_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->foreignId('province_id')->nullable(false)->change();
            $table->foreignId('regency_id')->nullable(false)->change();
        });

        Schema::table('universities', function (Blueprint $table) {
            $table->foreignId('province_id')->nullable(false)->change();
            $table->foreignId('regency_id')->nullable(false)->change();
        });
    }
};

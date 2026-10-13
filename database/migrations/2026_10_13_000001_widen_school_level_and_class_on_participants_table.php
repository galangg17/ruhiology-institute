<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `participants` MODIFY `school_level` VARCHAR(191) NULL, MODIFY `school_class` VARCHAR(191) NULL, MODIFY `entry_year` VARCHAR(50) NULL');
        }
    }

    public function down(): void
    {
        // No-op to preserve data
    }
};

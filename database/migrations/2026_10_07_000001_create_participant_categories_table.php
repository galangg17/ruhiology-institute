<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('participant_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('icon', 10)->nullable();
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // Insert initial default categories
        DB::table('participant_categories')->insert([
            [
                'name' => 'Pelajar',
                'code' => 'pelajar',
                'icon' => '🏫',
                'description' => 'Siswa SD / SMP / SMA / SMK / Sederajat',
                'order' => 1,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mahasiswa/i',
                'code' => 'mahasiswa',
                'icon' => '🎓',
                'description' => 'Mahasiswa Diploma / Sarjana / Pascasarjana',
                'order' => 2,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Umum',
                'code' => 'umum',
                'icon' => '👤',
                'description' => 'Masyarakat Umum / Profesional / Pegawai',
                'order' => 3,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participant_categories');
    }
};

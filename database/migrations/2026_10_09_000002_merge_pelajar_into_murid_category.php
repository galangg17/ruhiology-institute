<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Update all existing participants with category 'Pelajar' or 'pelajar' to 'MURID'
        DB::table('participants')
            ->where('category', 'Pelajar')
            ->orWhere('category', 'pelajar')
            ->update(['category' => 'MURID']);

        // 2. Locate MURID and Pelajar category rows in participant_categories
        $muridCat = DB::table('participant_categories')
            ->where('code', 'murid')
            ->orWhere('name', 'MURID')
            ->first();

        $pelajarCat = DB::table('participant_categories')
            ->where('code', 'pelajar')
            ->orWhere('name', 'Pelajar')
            ->first();

        if ($pelajarCat && $muridCat && $pelajarCat->id !== $muridCat->id) {
            // Re-link subcategories if any were on Pelajar
            DB::table('participant_sub_categories')
                ->where('category_id', $pelajarCat->id)
                ->update(['category_id' => $muridCat->id]);

            // Remove duplicate Pelajar category row
            DB::table('participant_categories')->where('id', $pelajarCat->id)->delete();
        } elseif ($pelajarCat && !$muridCat) {
            // Rename Pelajar to MURID
            DB::table('participant_categories')->where('id', $pelajarCat->id)->update([
                'name' => 'MURID',
                'code' => 'murid',
                'description' => 'Siswa SD / SMP / SMA / SMK / MA / Sederajat',
                'status' => 'active',
            ]);
        }

        // 3. Ensure official sub-categories exist for MURID
        $finalMurid = DB::table('participant_categories')
            ->where('code', 'murid')
            ->orWhere('name', 'MURID')
            ->first();

        if ($finalMurid) {
            $subs = [
                ['name' => 'SMA / Sederajat', 'code' => 'sma', 'detail_label' => 'Nama Sekolah (misal: SMAN 1 Jambi)'],
                ['name' => 'SMK / Sederajat', 'code' => 'smk', 'detail_label' => 'Nama Sekolah (misal: SMKN 1 Jambi)'],
                ['name' => 'MA (Madrasah Aliyah)', 'code' => 'ma', 'detail_label' => 'Nama Madrasah (misal: MAN 1 Jambi)'],
                ['name' => 'SMP / MTs', 'code' => 'smp', 'detail_label' => 'Nama SMP / MTs'],
                ['name' => 'SD / MI', 'code' => 'sd', 'detail_label' => 'Nama SD / MI'],
                ['name' => 'Sederajat Lainnya', 'code' => 'sederajat_lainnya', 'detail_label' => 'Nama Sekolah / Lembaga'],
            ];

            foreach ($subs as $orderIdx => $sub) {
                DB::table('participant_sub_categories')->updateOrInsert(
                    ['category_id' => $finalMurid->id, 'code' => $sub['code']],
                    [
                        'category_id' => $finalMurid->id,
                        'name' => $sub['name'],
                        'code' => $sub['code'],
                        'detail_label' => $sub['detail_label'],
                        'order' => $orderIdx + 1,
                        'status' => 'active',
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reversal needed
    }
};

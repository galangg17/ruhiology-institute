<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Instrument;
use App\Models\Program;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $instrument = Instrument::where('code', 'RQI-15')->first();
        $program = Program::first();

        Event::firstOrCreate(
            ['event_code' => 'PUBLIC-SELF'],
            [
                'title' => 'Asesmen Mandiri Publik / Umum',
                'access_type' => 'PUBLIC_SELF',
                'institution_name' => 'Ruhiology Institute',
                'program_id' => $program->id ?? null,
                'instrument_id' => $instrument->id ?? null,
                'status' => 'active',
                'description' => 'Sesi refleksi batin mandiri untuk masyarakat umum.'
            ]
        );

        Event::firstOrCreate(
            ['event_code' => 'RQ-EVT-JAMBI26'],
            [
                'title' => 'Uji Ruhiologi & Pelatihan Karakter Jambi 2026',
                'access_type' => 'EVENT_PROGRAM',
                'institution_name' => 'Kanwil Kemenag Provinsi Jambi',
                'program_id' => $program->id ?? null,
                'instrument_id' => $instrument->id ?? null,
                'start_date' => now(),
                'end_date' => now()->addMonths(3),
                'quota' => 250,
                'status' => 'active',
                'description' => 'Kegiatan pengukuran resmi tingkat kesadaran batin & vitalitas emosional peserta pelatihan.'
            ]
        );

        Event::firstOrCreate(
            ['event_code' => 'SMANTT'],
            [
                'title' => 'Ruhiologi Asesment',
                'access_type' => 'EVENT_PROGRAM',
                'institution_name' => 'SMAN Titian Teras Jambi',
                'program_id' => $program->id ?? null,
                'instrument_id' => $instrument->id ?? null,
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
                'status' => 'active',
                'target_category' => 'Pelajar',
                'province_id' => 5,
                'regency_id' => 10,
                'group_label' => 'Pilih Kelas',
                'custom_subcategories' => ['X-A', 'X-B', 'X-C', 'X-D', 'X-E', 'X-F'],
                'description' => 'Asesmen Ruhiologi SMAN Titian Teras Jambi.'
            ]
        );
    }
}

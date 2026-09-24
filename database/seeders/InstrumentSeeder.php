<?php

namespace Database\Seeders;

use App\Models\AssessmentPeriod;
use App\Models\Dimension;
use App\Models\Indicator;
use App\Models\Instrument;
use App\Models\Program;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\ScoringRule;
use Illuminate\Database\Seeder;

class InstrumentSeeder extends Seeder
{
    public function run(): void
    {
        $instrument = Instrument::create([
            'code' => 'RQI-V1',
            'name' => 'Instrumen Kecerdasan Ruhiologi (Ruhiology Quotient Inventory - Version 1.0)',
            'description' => 'Instrumen baku pengukuran potensi kecerdasan ruhiologi yang menempatkan ruh sebagai pusat kesadaran, kesucian niat, dan akhlak kemanusiaan.',
            'version' => '1.0',
            'instructions' => 'Bacalah setiap pernyataan dengan seksama. Pilihlah opsi jawaban yang paling mencerminkan kondisi dan perasaan Anda yang sebenarnya tanpa ada dorongan berpura-pura. Tidak ada jawaban salah.',
            'status' => 'active',
        ]);

        ScoringRule::create([
            'instrument_id' => $instrument->id,
            'scale_min' => 1,
            'scale_max' => 5,
            'reverse_mapping' => [
                '1' => 5,
                '2' => 4,
                '3' => 3,
                '4' => 2,
                '5' => 1,
            ],
            'interpretation_ranges' => [
                [
                    'min' => 85,
                    'max' => 100,
                    'level' => 'Sangat Tinggi (Ruhiology Mastery)',
                    'description' => 'Ruh menjadi pusat navigasi diri yang sangat dominan, memberikan kedamaian batin, integritas luhur, dan dampak aksi positif bagi sesama.'
                ],
                [
                    'min' => 70,
                    'max' => 84.99,
                    'level' => 'Tinggi (Ruhiology Competent)',
                    'description' => 'Kesadaran ruhiologi berkembang dengan sangat baik, dorongan spiritual terwujud dalam rutinitas harian.'
                ],
                [
                    'min' => 50,
                    'max' => 69.99,
                    'level' => 'Sedang (Ruhiology Developing)',
                    'description' => 'Memiliki kesadaran awal namun memerlukan penguatan dalam konsistensi amalan dan purifikasi jiwa.'
                ],
                [
                    'min' => 0,
                    'max' => 49.99,
                    'level' => 'Perlu Penguatan (Initial Stage)',
                    'description' => 'Memerlukan pembimbingan mendasar untuk menggali potensi ruh dan kesadaran tujuan hidup.'
                ],
            ],
        ]);

        // Dimensions
        $d1 = Dimension::create([
            'instrument_id' => $instrument->id,
            'code' => 'DIM-1',
            'name' => 'Kesadaran Transendental & Ruhani (Spiritual Transcendent Awareness)',
            'description' => 'Tingkat kesadaran akan kehadiran Tuhan dan penempatan ruh sebagai hakikat diri.',
            'order' => 1,
        ]);

        $d2 = Dimension::create([
            'instrument_id' => $instrument->id,
            'code' => 'DIM-2',
            'name' => 'Purifikasi Jiwa & Mental (Mental & Psychic Purity)',
            'description' => 'Kemampuan menjaga kebersihan niat, kejujuran batin, dan resistensi terhadap dorongan destruktif.',
            'order' => 2,
        ]);

        $d3 = Dimension::create([
            'instrument_id' => $instrument->id,
            'code' => 'DIM-3',
            'name' => 'Orientasi Niat & Orientasi Tujuan (Intentionality & Purpose)',
            'description' => 'Keterikatan setiap keputusan dan aktivitas dengan niat luhur demi keridhaan Tuhan.',
            'order' => 3,
        ]);

        $d4 = Dimension::create([
            'instrument_id' => $instrument->id,
            'code' => 'DIM-4',
            'name' => 'Kematangan Emosi & Empati Sosial (Socio-Emotional Harmony)',
            'description' => 'Ketenangan batin dalam menghadapi ujian hidup dan dorongan melayani sesama.',
            'order' => 4,
        ]);

        // Indicators
        $ind1 = Indicator::create(['dimension_id' => $d1->id, 'code' => 'IND-1.1', 'name' => 'Keterikatan Dzikir & Kedamaian Batin', 'order' => 1]);
        $ind2 = Indicator::create(['dimension_id' => $d2->id, 'code' => 'IND-2.1', 'name' => 'Muroqobah & Kebersihan Hati', 'order' => 1]);
        $ind3 = Indicator::create(['dimension_id' => $d3->id, 'code' => 'IND-3.1', 'name' => 'Ketulusan Niat (Ikhlas)', 'order' => 1]);
        $ind4 = Indicator::create(['dimension_id' => $d4->id, 'code' => 'IND-4.1', 'name' => 'Resiliensi Spiritual & Sabar', 'order' => 1]);

        // Questions List
        $questionsData = [
            [
                'dimension_id' => $d1->id,
                'indicator_id' => $ind1->id,
                'question_text' => 'Saya merenungi bahwa ruh adalah hakikat diri yang harus selalu terhubung dengan Sang Pencipta dalam setiap aktivitas.',
                'type' => 'likert',
                'scoring_direction' => 'normal',
                'order' => 1,
            ],
            [
                'dimension_id' => $d1->id,
                'indicator_id' => $ind1->id,
                'question_text' => 'Saya sering merasa cemas dan hampa meskipun telah meraih pencapaian materi atau pujian orang lain.',
                'type' => 'likert',
                'scoring_direction' => 'reverse', // REVERSE ITEM
                'order' => 2,
            ],
            [
                'dimension_id' => $d2->id,
                'indicator_id' => $ind2->id,
                'question_text' => 'Saya berusaha segera memohon ampunan dan memperbaiki kebiasaan ketika menyadari kekhilafan diri.',
                'type' => 'likert',
                'scoring_direction' => 'normal',
                'order' => 3,
            ],
            [
                'dimension_id' => $d2->id,
                'indicator_id' => $ind2->id,
                'question_text' => 'Saya mudah menyimpan rasa iri hati atau emosi negatif saat melihat keberhasilan orang lain.',
                'type' => 'likert',
                'scoring_direction' => 'reverse', // REVERSE ITEM
                'order' => 4,
            ],
            [
                'dimension_id' => $d3->id,
                'indicator_id' => $ind3->id,
                'question_text' => 'Sebelum memulai pekerjaan atau tugas akademik, saya secara sadar meniatkannya untuk ibadah dan kebermanfaatan.',
                'type' => 'likert',
                'scoring_direction' => 'normal',
                'order' => 5,
            ],
            [
                'dimension_id' => $d4->id,
                'indicator_id' => $ind4->id,
                'question_text' => 'Ketika menghadapi musibah atau kegagalan, saya dapat tetap tenang dengan melihat hikmah spiritual di baliknya.',
                'type' => 'likert',
                'scoring_direction' => 'normal',
                'order' => 6,
            ],
        ];

        $options = [
            ['text' => 'Sangat Tidak Sesuai', 'value' => 1, 'order' => 1],
            ['text' => 'Tidak Sesuai', 'value' => 2, 'order' => 2],
            ['text' => 'Ragu-ragu / Netral', 'value' => 3, 'order' => 3],
            ['text' => 'Sesuai', 'value' => 4, 'order' => 4],
            ['text' => 'Sangat Sesuai', 'value' => 5, 'order' => 5],
        ];

        foreach ($questionsData as $qData) {
            $question = Question::create([
                'instrument_id' => $instrument->id,
                'dimension_id' => $qData['dimension_id'],
                'indicator_id' => $qData['indicator_id'],
                'question_text' => $qData['question_text'],
                'type' => $qData['type'],
                'scoring_direction' => $qData['scoring_direction'],
                'order' => $qData['order'],
                'status' => 'active',
            ]);

            foreach ($options as $opt) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $opt['text'],
                    'option_value' => $opt['value'],
                    'order' => $opt['order'],
                ]);
            }
        }

        // Active Assessment Period
        $program = Program::first();
        if ($program) {
            AssessmentPeriod::create([
                'program_id' => $program->id,
                'instrument_id' => $instrument->id,
                'title' => 'Asesmen Kecerdasan Ruhiologi (Pretest & Posttest) Semester Ganjil 2026',
                'period_code' => 'PER-RQ-2026-01',
                'pretest_start' => now()->subDays(10),
                'pretest_end' => now()->addDays(20),
                'posttest_start' => now()->subDays(2),
                'posttest_end' => now()->addDays(30),
                'status' => 'active',
            ]);
        }
    }
}

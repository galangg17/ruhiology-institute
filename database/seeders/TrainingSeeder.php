<?php

namespace Database\Seeders;

use App\Models\TrainingBatch;
use App\Models\TrainingProgram;
use App\Models\TrainingRegistration;
use Illuminate\Database\Seeder;

class TrainingSeeder extends Seeder
{
    public function run(): void
    {
        $t1 = TrainingProgram::create([
            'title' => 'Certified Ruhiology Educator & Trainer (CRET)',
            'slug' => 'certified-ruhiology-educator-trainer',
            'description' => 'Pelatihan sertifikasi utama untuk pendidik, dosen, dan fasilitator pengembangan karakter berbasis konsep Kecerdasan Ruhiologi (RQ). Peserta akan dibekali instrumen pemetaan RQ, teknik coaching spiritual, dan metode pengajaran holistik.',
            'category' => 'Sertifikasi Nasional',
            'trainer' => 'Prof. Dr. Iskandar Nazari, S.Ag., M.Pd., M.S.I., M.H., Ph.D. & Tim Ahli',
            'duration' => '3 Hari (24 JP)',
            'location' => 'Hotel Grand Royale Jambi & Hybrid Zoom',
            'is_online' => false,
            'price' => 1500000.00,
            'quota' => 40,
            'status' => 'published',
        ]);

        $b1 = TrainingBatch::create([
            'training_id' => $t1->id,
            'batch_name' => 'Angkatan I - Oktober 2026',
            'start_date' => now()->addDays(15),
            'end_date' => now()->addDays(17),
            'registration_open' => now()->subDays(10),
            'registration_close' => now()->addDays(10),
            'quota' => 40,
            'status' => 'open',
        ]);

        TrainingRegistration::create([
            'registration_number' => 'TRG-RET-001',
            'training_id' => $t1->id,
            'batch_id' => $b1->id,
            'participant_name' => 'Dr. H. Rahmad Wijaya, M.Pd.',
            'email' => 'rahmad.wijaya@gmail.com',
            'phone' => '081274009988',
            'institution' => 'STAI Al-Azhar Jambi',
            'price' => 1500000.00,
            'payment_status' => 'verified',
            'registration_status' => 'approved',
            'admin_notes' => 'Pembayaran via Bank Mandiri telah diverifikasi.',
        ]);

        $t2 = TrainingProgram::create([
            'title' => 'Workshop Pemetaan RQ & Purifikasi Mental Mandiri',
            'slug' => 'workshop-pemetaan-rq-purifikasi-mental',
            'description' => 'Workshop intensif daring selama 1 hari untuk umum dan profesional muda yang ingin mengenali kecerdasan ruhiologi pribadi, mengatasi kebuntuan mental, dan menumbuhkan ketenangan batin.',
            'category' => 'Workshop Short Course',
            'trainer' => 'Tim Trainer Utama Ruhiology Institute',
            'duration' => '1 Hari (6 JP)',
            'location' => 'Zoom Executive Room',
            'is_online' => true,
            'price' => 250000.00,
            'quota' => 100,
            'status' => 'published',
        ]);

        TrainingBatch::create([
            'training_id' => $t2->id,
            'batch_name' => 'Batch Online November 2026',
            'start_date' => now()->addDays(30),
            'end_date' => now()->addDays(30),
            'registration_open' => now()->subDays(5),
            'registration_close' => now()->addDays(25),
            'quota' => 100,
            'status' => 'open',
        ]);
    }
}

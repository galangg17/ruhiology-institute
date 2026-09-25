<?php

namespace Database\Seeders;

use App\Models\Institution;
use App\Models\Program;
use Illuminate\Database\Seeder;

class InstitutionAndProgramSeeder extends Seeder
{
    public function run(): void
    {
        $uin = Institution::firstOrCreate(
            ['code' => 'UIN-STS'],
            [
                'name' => 'UIN Sulthan Thaha Saifuddin Jambi',
                'type' => 'University',
                'email' => 'info@uinjambi.ac.id',
                'phone' => '0741-60783',
                'address' => 'Jl. Jambi - Muara Bulian KM. 16, Simpang Sungai Duren, Muaro Jambi',
                'contact_person' => 'Prof. Dr. Iskandar Nazari',
                'status' => 'active',
            ]
        );

        $kemenag = Institution::firstOrCreate(
            ['code' => 'KEMENAG-RI'],
            [
                'name' => 'Kementerian Agama Republik Indonesia',
                'type' => 'Government',
                'email' => 'humas@kemenag.go.id',
                'phone' => '021-3811244',
                'address' => 'Jl. Lapangan Banteng Barat No. 3-4 Jakarta Pusat',
                'contact_person' => 'Drs. H. M. Ridwan',
                'status' => 'active',
            ]
        );

        $riCenter = Institution::firstOrCreate(
            ['code' => 'RUHIOLOGY-CENTER'],
            [
                'name' => 'Ruhiology Institute National Center',
                'type' => 'Institute',
                'email' => 'contact@ruhiologyinstitute.com',
                'phone' => '081274110000',
                'address' => 'Gedung Ruhiology Institute, Kota Jambi',
                'contact_person' => 'Sekretariat Utama',
                'status' => 'active',
            ]
        );

        Program::firstOrCreate(
            ['code' => 'PROG-RQ-STUDENT-2026'],
            [
                'institution_id' => $uin->id,
                'name' => 'Program Pengembangan Kecerdasan Ruhiologi Mahasiswa UIN STS Jambi',
                'description' => 'Program assessment dan pelatihan dasar RQ untuk mahasiswa angkatan 2026.',
                'start_date' => now()->subDays(30),
                'end_date' => now()->addDays(60),
                'status' => 'active',
            ]
        );

        Program::firstOrCreate(
            ['code' => 'PROG-RQ-TEACHER-2026'],
            [
                'institution_id' => $kemenag->id,
                'name' => 'Asesmen Ruhiologi Guru & Pendidik Karakter Nusantara',
                'description' => 'Program pengukuran awal dan evaluasi kepemimpinan berbasis ruh spiritualitas pendidik.',
                'start_date' => now()->subDays(15),
                'end_date' => now()->addDays(45),
                'status' => 'active',
            ]
        );

        Program::firstOrCreate(
            ['code' => 'PROG-RQ-EXECUTIVE-2026'],
            [
                'institution_id' => $riCenter->id,
                'name' => 'Ruhiology Executive Leadership Development',
                'description' => 'Program konsultasi dan pelatihan RQ tingkat eksekutif bagi pimpinan lembaga.',
                'start_date' => now()->subDays(5),
                'end_date' => now()->addDays(90),
                'status' => 'active',
            ]
        );
    }
}

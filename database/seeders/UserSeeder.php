<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Super Admin
        User::firstOrCreate(
            ['email' => 'admin@ruhiologyinstitute.com'],
            [
                'name' => 'Super Admin Ruhiology',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
                'phone' => '081274110001',
                'status' => 'active',
            ]
        );

        // 2. Operational Admin
        User::firstOrCreate(
            ['email' => 'operasional@ruhiologyinstitute.com'],
            [
                'name' => 'Admin Operasional',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '081274110002',
                'status' => 'active',
            ]
        );

        // 3. Assessment Manager
        User::firstOrCreate(
            ['email' => 'assessment@ruhiologyinstitute.com'],
            [
                'name' => 'Assessment Specialist',
                'password' => Hash::make('password123'),
                'role' => 'assessment_manager',
                'phone' => '081274110003',
                'status' => 'active',
            ]
        );

        // 4. Content Manager
        User::firstOrCreate(
            ['email' => 'content@ruhiologyinstitute.com'],
            [
                'name' => 'Content Manager',
                'password' => Hash::make('password123'),
                'role' => 'content_manager',
                'phone' => '081274110004',
                'status' => 'active',
            ]
        );

        // 5. Training Manager
        User::firstOrCreate(
            ['email' => 'training@ruhiologyinstitute.com'],
            [
                'name' => 'Training Coordinator',
                'password' => Hash::make('password123'),
                'role' => 'training_manager',
                'phone' => '081274110005',
                'status' => 'active',
            ]
        );

        // 6. Store Manager
        User::firstOrCreate(
            ['email' => 'store@ruhiologyinstitute.com'],
            [
                'name' => 'Store Manager',
                'password' => Hash::make('password123'),
                'role' => 'store_manager',
                'phone' => '081274110006',
                'status' => 'active',
            ]
        );

        // 7. Consultation Manager
        User::firstOrCreate(
            ['email' => 'consultation@ruhiologyinstitute.com'],
            [
                'name' => 'Consultation Advisor',
                'password' => Hash::make('password123'),
                'role' => 'consultation_manager',
                'phone' => '081274110007',
                'status' => 'active',
            ]
        );

        // 8. Sample Participant User
        User::firstOrCreate(
            ['email' => 'fauzi@uinjambi.ac.id'],
            [
                'name' => 'Ahmad Fauzi',
                'password' => Hash::make('password123'),
                'role' => 'participant',
                'phone' => '085266123456',
                'status' => 'active',
            ]
        );
    }
}

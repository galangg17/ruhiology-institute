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
        User::create([
            'name' => 'Super Admin Ruhiology',
            'email' => 'admin@ruhiologyinstitute.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'phone' => '081274110001',
            'status' => 'active',
        ]);

        // 2. Operational Admin
        User::create([
            'name' => 'Admin Operasional',
            'email' => 'operasional@ruhiologyinstitute.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '081274110002',
            'status' => 'active',
        ]);

        // 3. Assessment Manager
        User::create([
            'name' => 'Assessment Specialist',
            'email' => 'assessment@ruhiologyinstitute.com',
            'password' => Hash::make('password123'),
            'role' => 'assessment_manager',
            'phone' => '081274110003',
            'status' => 'active',
        ]);

        // 4. Content Manager
        User::create([
            'name' => 'Content Manager',
            'email' => 'content@ruhiologyinstitute.com',
            'password' => Hash::make('password123'),
            'role' => 'content_manager',
            'phone' => '081274110004',
            'status' => 'active',
        ]);

        // 5. Training Manager
        User::create([
            'name' => 'Training Coordinator',
            'email' => 'training@ruhiologyinstitute.com',
            'password' => Hash::make('password123'),
            'role' => 'training_manager',
            'phone' => '081274110005',
            'status' => 'active',
        ]);

        // 6. Store Manager
        User::create([
            'name' => 'Store Manager',
            'email' => 'store@ruhiologyinstitute.com',
            'password' => Hash::make('password123'),
            'role' => 'store_manager',
            'phone' => '081274110006',
            'status' => 'active',
        ]);

        // 7. Consultation Manager
        User::create([
            'name' => 'Consultation Advisor',
            'email' => 'consultation@ruhiologyinstitute.com',
            'password' => Hash::make('password123'),
            'role' => 'consultation_manager',
            'phone' => '081274110007',
            'status' => 'active',
        ]);

        // 8. Sample Participant User
        User::create([
            'name' => 'Ahmad Fauzi',
            'email' => 'fauzi@uinjambi.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'participant',
            'phone' => '085266123456',
            'status' => 'active',
        ]);
    }
}

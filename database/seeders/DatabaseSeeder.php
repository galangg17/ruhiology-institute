<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            MasterDataSeeder::class,
            InstitutionAndProgramSeeder::class,
            InstrumentSeeder::class,
            ParticipantAndAssessmentSeeder::class,
            TrainingSeeder::class,
            CatalogSeeder::class,
            CmsSeeder::class,
            SettingSeeder::class,
        ]);
    }
}

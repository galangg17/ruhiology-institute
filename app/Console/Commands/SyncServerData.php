<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SyncServerData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-all-server-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'One-click command to seed master data (provinces, regencies, RQI/WHO-5 questions) and import Al-Azhar dataset into target event on production server';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("==========================================");
        $this->info("🚀 SYNCHRONIZING ALL SERVER DATA & SEEDS");
        $this->info("==========================================");

        // 1. Run Migrations
        $this->info("1/4. Running Database Migrations...");
        Artisan::call('migrate', ['--force' => true]);
        $this->info(Artisan::output());

        // 2. Run Master Data & User Seeders
        $this->info("2/4. Seeding Admin Users & Master Data (38 Provinces, 517 Regencies, 20 Questions)...");
        Artisan::call('db:seed', ['--class' => 'UserSeeder', '--force' => true]);
        Artisan::call('db:seed', ['--class' => 'MasterDataSeeder', '--force' => true]);
        $this->info(Artisan::output());

        // 3. Import Al-Azhar Event Data
        $this->info("3/4. Importing & Linking Al-Azhar Jambi Event Dataset (80 Students)...");
        Artisan::call('app:import-alazhar');
        $this->info(Artisan::output());

        // 4. Clear Cache
        $this->info("4/4. Clearing & Refreshing Laravel Cache...");
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');

        $this->newLine();
        $this->info("==========================================");
        $this->info("✅ ALL SERVER DATA & CODE FULLY SYNCHRONIZED!");
        $this->info("==========================================");

        return 0;
    }
}

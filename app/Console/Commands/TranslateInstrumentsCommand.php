<?php

namespace App\Console\Commands;

use App\Services\TranslationService;
use Illuminate\Console\Command;

class TranslateInstrumentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ruhiology:translate-instruments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically translate all instruments, dimensions, questions, and options into official English';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting automated instrument translation into English...');

        $stats = TranslationService::translateAllInstruments();

        $this->table(
            ['Entity', 'Count Translated'],
            [
                ['Instruments', $stats['instruments']],
                ['Dimensions', $stats['dimensions']],
                ['Questions', $stats['questions']],
                ['Question Options', $stats['options']],
            ]
        );

        $this->info('All instruments successfully translated into English!');

        return Command::SUCCESS;
    }
}

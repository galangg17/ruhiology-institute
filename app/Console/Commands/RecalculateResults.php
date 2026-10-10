<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AssessmentSubmission;
use App\Services\ResultService;

class RecalculateResults extends Command
{
    protected $signature = 'results:recalculate';
    protected $description = 'Recalculate all assessment results to ensure accurate RQI and WHO-5 scoring';

    public function handle(ResultService $resultService)
    {
        $this->info("Starting recalculation of assessment results...");
        $submissions = AssessmentSubmission::where('status', 'submitted')->get();
        $count = 0;

        foreach ($submissions as $submission) {
            $resultService->generateResult($submission);
            $count++;
        }

        $this->info("Successfully recalculated {$count} submission results!");
        return 0;
    }
}

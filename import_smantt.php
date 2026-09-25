<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\Models\Participant;
use App\Models\AssessmentSubmission;

$sqlFile = __DIR__ . '/dump_smantt_data.sql';
if (!file_exists($sqlFile)) {
    echo "File dump_smantt_data.sql tidak ditemukan.\n";
    exit(1);
}

echo "Memulai import data peserta SMAN Titian Teras ke database...\n";
$sql = file_get_contents($sqlFile);
DB::unprepared($sql);

// Sync event_id mapping for cPanel DB
$smanttEvent = Event::where('event_code', 'SMANTT')->first();
if ($smanttEvent) {
    // Update participants that entered SMANTT event
    Participant::whereIn('sub_category', ['X-A', 'X-B', 'X-C', 'X-D', 'X-E', 'X-F'])
        ->orWhere('event_id', 9)
        ->update(['event_id' => $smanttEvent->id]);

    // Update submissions for SMANTT participants
    AssessmentSubmission::whereIn('participant_id', Participant::where('event_id', $smanttEvent->id)->pluck('id'))
        ->update(['event_id' => $smanttEvent->id]);
}

$submissionCount = AssessmentSubmission::where('event_id', $smanttEvent?->id ?? 9)->count();
$participantCount = Participant::where('event_id', $smanttEvent?->id ?? 9)->count();

echo "IMPORT BERHASIL! {$participantCount} peserta & {$submissionCount} hasil tes SMAN TT telah berhasil dihubungkan ke Event SMANTT (ID: " . ($smanttEvent->id ?? '9') . ").\n";

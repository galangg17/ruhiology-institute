<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "PROVINCES FOR RIAU / KEPULAUAN RIAU:\n";
$provs = \App\Models\Province::where('name', 'like', '%Riau%')->get();
foreach ($provs as $p) {
    echo "ID: {$p->id} | Code: {$p->code} | Name: {$p->name}\n";
    $regs = \App\Models\Regency::where('province_id', $p->id)->get();
    echo "  Regencies count for ID {$p->id}: " . $regs->count() . "\n";
    foreach ($regs as $r) {
        echo "   - Reg ID: {$r->id} | Code: {$r->code} | Name: {$r->name} | ProvID in DB: {$r->province_id} | Status: {$r->status}\n";
    }
}

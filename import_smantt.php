<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$sqlFile = __DIR__ . '/dump_smantt_data.sql';
if (!file_exists($sqlFile)) {
    echo "File dump_smantt_data.sql tidak ditemukan.\n";
    exit(1);
}

echo "Memulai import data peserta SMAN Titian Teras ke database...\n";
$sql = file_get_contents($sqlFile);
DB::unprepared($sql);
echo "IMPORT BERHASIL! Seluruh data 170+ peserta SMAN TT & hasil asesmen telah berhasil diimpor.\n";

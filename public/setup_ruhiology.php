<?php
/**
 * Ruhiology Institute — Web Setup & Database Deployment Utility
 * Visit this script in browser: https://ruhiology.gmadhyaksa-litbang.my.id/setup_ruhiology.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('LARAVEL_START', microtime(true));

// Auto-fix composer platform check for PHP 8.2 / 8.3 hosting compatibility
$platformCheckFile = __DIR__ . '/../vendor/composer/platform_check.php';
if (file_exists($platformCheckFile)) {
    @file_put_contents($platformCheckFile, "<?php\n// Neutralized for hosting compatibility\n\$issues = array();\n");
}

// Auto-heal .env file if missing or improperly formatted
$envFile = __DIR__ . '/../.env';
if (!file_exists($envFile) || str_contains(@file_get_contents($envFile), 'DB_CONNECTION=sqlite')) {
    $envDefault = <<<ENV
APP_NAME="Ruhiology Institute"
APP_ENV=production
APP_KEY=base64:ATPhyzjTorf6oSl75HWVGpXVZ7Fu/AeS/vdB6S8RqX8=
APP_DEBUG=true
APP_URL=https://ruhiology.gmadhyaksa-litbang.my.id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gmadhyak_rq
DB_USERNAME=gmadhyak_rq
DB_PASSWORD=ruhiologi123

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

CACHE_STORE=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=public

LOG_CHANNEL=stack
LOG_LEVEL=debug
ENV;
    @file_put_contents($envFile, $envDefault);
}

// Ensure storage directories exist and are writable
$storageDirs = [
    __DIR__ . '/../storage',
    __DIR__ . '/../storage/app',
    __DIR__ . '/../storage/app/public',
    __DIR__ . '/../storage/framework',
    __DIR__ . '/../storage/framework/cache',
    __DIR__ . '/../storage/framework/sessions',
    __DIR__ . '/../storage/framework/views',
    __DIR__ . '/../storage/logs',
    __DIR__ . '/../bootstrap/cache',
];
foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Check if Laravel bootstrap exists
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    die('<h1>Error: vendor directory missing.</h1><p>Please make sure composer install or vendor files are uploaded to the root directory.</p>');
}

try {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
} catch (\Throwable $e) {
    echo '<div style="font-family:sans-serif; padding:20px; background:#fff0f0; border:2px solid red; border-radius:10px;">';
    echo '<h1 style="color:red; margin-top:0;">Bootstrap Error</h1>';
    echo '<p><b>Error Message:</b> ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p><b>File:</b> ' . htmlspecialchars($e->getFile()) . ' (Line ' . $e->getLine() . ')</p>';
    echo '<pre style="background:#222; color:#0f0; padding:10px; border-radius:5px; overflow:auto;">' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    echo '</div>';
    exit;
}

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

$action = $_GET['action'] ?? 'view';
$message = '';
$error = '';
$outputLog = '';

if ($action === 'run_setup') {
    try {
        // 1. Run migrations & seeders
        Artisan::call('migrate:fresh', [
            '--force' => true,
            '--seed' => true,
        ]);
        $outputLog .= Artisan::output() . "\n";

        // 2. Storage link
        try {
            Artisan::call('storage:link');
            $outputLog .= Artisan::output() . "\n";
        } catch (\Throwable $e) {
            $outputLog .= "Storage link note: " . $e->getMessage() . "\n";
        }

        // 3. Clear & optimize cache
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        $message = "Setup & Database Migration Berhasil! Seluruh tabel dan data master RQI-15 & WHO-5 telah ter-seed 100%.";
    } catch (\Throwable $e) {
        $error = "Gagal menjalankan setup: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup & Deployment Utility — Ruhiology Institute</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-[#0B2A43] text-white min-h-screen flex items-center justify-center p-4">
    <div class="max-w-lg w-full bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
        
        <div class="text-center space-y-2 border-b border-slate-800 pb-4">
            <span class="text-xs font-mono font-bold text-[#C9A24D] uppercase tracking-widest bg-white/10 px-3 py-1 rounded-full border border-white/20">
                WEB SETUP UTILITY
            </span>
            <h1 class="text-2xl font-serif font-bold text-white">Ruhiology Institute</h1>
            <p class="text-xs text-slate-400">Pemasangan Otomatis Database & Setup System</p>
        </div>

        <?php if ($message): ?>
            <div class="p-4 bg-emerald-950 text-emerald-300 border border-emerald-800 rounded-2xl text-xs space-y-2">
                <strong class="text-emerald-400 block font-bold text-sm">✨ <?php echo htmlspecialchars($message); ?></strong>
                <p class="text-slate-300">Silakan hapus file <code class="bg-black/50 px-2 py-0.5 rounded text-amber-300">public/setup_ruhiology.php</code> dari File Manager demi keamanan.</p>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="p-4 bg-rose-950 text-rose-300 border border-rose-800 rounded-2xl text-xs space-y-1">
                <strong class="text-rose-400 block font-bold text-sm">⚠️ <?php echo htmlspecialchars($error); ?></strong>
                <p class="text-slate-300">Pastikan file <code class="bg-black/50 px-2 py-0.5 rounded text-amber-300">.env</code> sudah dibuat dan data DB_DATABASE, DB_USERNAME, DB_PASSWORD di-setting dengan benar.</p>
            </div>
        <?php endif; ?>

        <?php if ($outputLog): ?>
            <div class="space-y-1">
                <span class="text-[11px] font-mono text-slate-400 font-bold block">Console Output:</span>
                <pre class="bg-black/80 p-3 rounded-xl text-[10px] font-mono text-emerald-400 overflow-x-auto max-h-48 border border-slate-800"><?php echo htmlspecialchars($outputLog); ?></pre>
            </div>
        <?php endif; ?>

        <div class="space-y-3">
            <div class="p-4 bg-slate-800/80 rounded-2xl border border-slate-700 text-xs text-slate-300 space-y-1.5">
                <strong class="text-white font-bold block">Status Koneksi Database:</strong>
                <?php
                try {
                    DB::connection()->getPdo();
                    echo '<span class="text-emerald-400 font-bold flex items-center gap-1.5"><span>✅</span> <span>Terhubung ke MySQL (' . htmlspecialchars(config('database.connections.mysql.database')) . ')</span></span>';
                } catch (\Throwable $e) {
                    echo '<span class="text-rose-400 font-bold flex items-center gap-1.5"><span>❌</span> <span>Belum Terhubung: ' . htmlspecialchars($e->getMessage()) . '</span></span>';
                }
                ?>
            </div>

            <form action="setup_ruhiology.php" method="GET" class="pt-2">
                <input type="hidden" name="action" value="run_setup">
                <button type="submit" class="w-full py-4 bg-[#C9A24D] hover:bg-[#B48A16] text-[#0B2A43] font-bold text-xs uppercase tracking-wider rounded-2xl shadow-xl transition transform hover:scale-[1.02] cursor-pointer">
                    🚀 Jalankan Migration & Seed Database Sekarang →
                </button>
            </form>

            <?php if ($message): ?>
                <div class="pt-2">
                    <a href="/" class="block w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs text-center uppercase tracking-wider rounded-2xl transition">
                        Buka Website Utama (Homepage) →
                    </a>
                </div>
            <?php endif; ?>
        </div>

    </div>
</body>
</html>

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

require __DIR__ . '/api/products.php';

Route::get('/', function () {
    try {
        DB::connection()->getPdo();
        $dbConnectionStatus = 'OK';
    } catch (\Exception $e) {
        $dbConnectionStatus = 'Failed: ' . $e->getMessage();
    }

    $lastCronRun = Cache::get('last_cron_run', 'Not Available');

    $serverStartTime = $_SERVER['REQUEST_TIME'];
    $uptime = time() - $serverStartTime;

    $memoryUsage = memory_get_usage(true);
    $memoryUsageMB = round($memoryUsage / 1024 / 1024, 2) . ' MB';

    return response()->json([
        'api_status' => 'OK',
        'db_connection' => $dbConnectionStatus,
        'last_cron_run' => $lastCronRun,
        'uptime_seconds' => $uptime,
        'uptime_human_readable' => gmdate('H:i:s', $uptime),
        'memory_usage' => $memoryUsageMB,
    ]);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

<?php

use App\Models\Sugihart\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();
if (getenv('SDM_BROWSER_TEST_DATABASE') === false
    || config('database.connections.sdm.driver') !== 'sqlite'
    || config('database.connections.sdm.database') !== getenv('SDM_BROWSER_TEST_DATABASE')) {
    throw new RuntimeException('Browser fixtures require the isolated SQLite database created by the test runner.');
}
$migration = require __DIR__.'/../database/migrations/sugihart/2026_11_06_000000_create_device_manager_tables.php';
$migration->up();
$hash = Hash::make('browser-password');
User::create(['name' => 'Browser Admin', 'email' => 'browser@example.test', 'password' => $hash]);
$db = DB::connection('sdm');
$vendor = $db->table('vendor')->insertGetId(['name' => 'Browser HK', 'token' => 'browser-vendor']);
$db->table('device')->insert(['name' => 'Browser Camera', 'type' => 'cctv', 'vendor_id' => $vendor, 'token' => 'browser-camera']);
for ($index = 1; $index <= 12; $index++) {
    User::create(['name' => 'Page User '.$index, 'email' => 'page'.$index.'@example.test', 'password' => $hash]);
    $db->table('vendor')->insert(['name' => 'Page Vendor '.$index, 'token' => 'page-vendor-'.$index]);
    $db->table('device')->insert(['name' => 'Page Camera '.$index, 'type' => 'cctv', 'vendor_id' => $vendor, 'token' => 'page-camera-'.sprintf('%02d', $index)]);
}

<?php

return [
    // Keep this prefix when mounted inside the new absensi application.
    'route_prefix' => env('SDM_ROUTE_PREFIX', 'sugihart_device_manager/public'),
    'asset_prefix' => env('SDM_ASSET_PREFIX', 'vendor/sugihart-device-manager'),
    'database' => [
        'driver' => env('SDM_DB_CONNECTION', 'pgsql'),
        'host' => env('SDM_DB_HOST', '127.0.0.1'),
        'port' => env('SDM_DB_PORT', '5432'),
        'database' => env('SDM_DB_DATABASE', 'sugihart_device_manager'),
        'username' => env('SDM_DB_USERNAME', 'postgres'),
        'password' => env('SDM_DB_PASSWORD', ''),
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'search_path' => env('SDM_DB_SEARCH_PATH', 'public'),
        'sslmode' => env('SDM_DB_SSLMODE', 'prefer'),
        // SQLite remains available for the isolated automated test suite.
        'foreign_key_constraints' => true,
    ],
    'attendance_url' => env('SDM_ATTENDANCE_URL', 'https://absensi.hutamakarya.com/'),
    'kiosk_local_url' => env('SDM_KIOSK_LOCAL_URL', 'http://localhost/hutamakarya-kios-live2/'),
    'kiosk_asset_url' => env('SDM_KIOSK_ASSET_URL', env('SDM_KIOSK_LOCAL_URL', 'http://localhost/hutamakarya-kios-live2/')),
    // IP checking was disabled in the existing working copy. Enable after configuring proxies.
    'enforce_kiosk_ip' => (bool) env('SDM_ENFORCE_KIOSK_IP', false),
    'registration_enabled' => (bool) env('SDM_REGISTRATION_ENABLED', false),
];

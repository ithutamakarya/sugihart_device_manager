<?php

use Illuminate\Support\Facades\Route;
use Sugihart\DeviceManager\Http\Controllers\ApiController;

// Stateless: no login, sessions or additional /api prefix.
Route::get('api/get_data_cctv/{token}', [ApiController::class, 'getDeviceCCTV'])->name('api.cctv');
Route::get('api/get_kios/{token}', [ApiController::class, 'getDeviceKios'])->name('api.kios');
Route::get('api/get_device/{token}', [ApiController::class, 'getDevice'])->name('api.devices');
Route::get('api/get_render_kios/{token}', [ApiController::class, 'render_kios'])->name('api.render');

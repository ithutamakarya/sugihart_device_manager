<?php

use App\Http\Controllers\Sugihart\DeviceManagerController;
use App\Http\Middleware\Sugihart\Authenticate;
use Illuminate\Support\Facades\Route;

// Stateless: no login, sessions or additional /api prefix.
Route::get('api/get_data_cctv/{token}', [DeviceManagerController::class, 'getDeviceCCTV'])->name('api.cctv');
Route::get('api/get_kios/{token}', [DeviceManagerController::class, 'getDeviceKios'])->name('api.kios');
Route::get('api/get_device/{token}', [DeviceManagerController::class, 'getDevice'])->name('api.devices');
Route::get('api/get_render_kios/{token}', [DeviceManagerController::class, 'render_kios'])->name('api.render');

Route::middleware('web')->group(function (): void {
    Route::get('login', [DeviceManagerController::class, 'loginForm'])->name('login');
    Route::post('login', [DeviceManagerController::class, 'login'])->name('login.store');
    Route::match(['get', 'post'], 'logout', [DeviceManagerController::class, 'logout'])->name('logout');
    Route::get('register', [DeviceManagerController::class, 'registerForm'])->name('register');
    Route::post('register', [DeviceManagerController::class, 'register'])->name('register.store');
    Route::get('password/reset', [DeviceManagerController::class, 'passwordRequest'])->name('password.request');
    Route::post('password/email', [DeviceManagerController::class, 'passwordEmail'])->middleware('throttle:6,1')->name('password.email');
    Route::get('password/reset/{token}', [DeviceManagerController::class, 'passwordForm'])->name('password.reset');
    Route::post('password/reset', [DeviceManagerController::class, 'passwordReset'])->middleware('throttle:6,1')->name('password.update');

    Route::middleware(Authenticate::class)->group(function (): void {
        Route::get('/', [DeviceManagerController::class, 'index'])->name('home');
        Route::get('main/user_list', [DeviceManagerController::class, 'users'])->name('users');
        Route::get('main/vendor', [DeviceManagerController::class, 'vendor'])->name('vendors');
        Route::get('main/device/{vendor_id}', [DeviceManagerController::class, 'device'])->name('devices');
        Route::get('main/log_activity', [DeviceManagerController::class, 'log_activity'])->name('logs');
        foreach (['user', 'vendor', 'device'] as $resource) {
            Route::post('main/create_'.$resource, [DeviceManagerController::class, $resource.'_create'])->name($resource.'.create');
            Route::post('main/update_'.$resource, [DeviceManagerController::class, $resource.'_update'])->name($resource.'.update');
            $path = 'main/delete_'.$resource.'/{id}'.($resource === 'device' ? '/{vendor_id}' : '');
            Route::post($path, [DeviceManagerController::class, $resource.'_delete'])->name($resource.'.delete');
        }
    });

});

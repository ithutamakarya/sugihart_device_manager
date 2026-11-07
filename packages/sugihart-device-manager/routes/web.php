<?php

use Illuminate\Support\Facades\Route;
use Sugihart\DeviceManager\Http\Controllers\AuthController;
use Sugihart\DeviceManager\Http\Controllers\MainActionController;
use Sugihart\DeviceManager\Http\Controllers\MainController;
use Sugihart\DeviceManager\Http\Middleware\Authenticate;

Route::get('login', [AuthController::class, 'loginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.store');
Route::match(['get', 'post'], 'logout', [AuthController::class, 'logout'])->name('logout');
Route::get('register', [AuthController::class, 'registerForm'])->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register.store');
Route::get('password/reset', [AuthController::class, 'passwordRequest'])->name('password.request');
Route::post('password/email', [AuthController::class, 'passwordEmail'])->middleware('throttle:6,1')->name('password.email');
Route::get('password/reset/{token}', [AuthController::class, 'passwordForm'])->name('password.reset');
Route::post('password/reset', [AuthController::class, 'passwordReset'])->middleware('throttle:6,1')->name('password.update');

Route::middleware(Authenticate::class)->group(function (): void {
    Route::get('/', [MainController::class, 'index'])->name('home');
    Route::get('main/user_list', [MainController::class, 'users'])->name('users');
    Route::get('main/vendor', [MainController::class, 'vendor'])->name('vendors');
    Route::get('main/device/{vendor_id}', [MainController::class, 'device'])->name('devices');
    Route::get('main/log_activity', [MainController::class, 'log_activity'])->name('logs');
    foreach (['user', 'vendor', 'device'] as $resource) {
        Route::post('main/create_'.$resource, [MainActionController::class, $resource.'_create'])->name($resource.'.create');
        Route::post('main/update_'.$resource, [MainActionController::class, $resource.'_update'])->name($resource.'.update');
        $path = 'main/delete_'.$resource.'/{id}'.($resource === 'device' ? '/{vendor_id}' : '');
        Route::post($path, [MainActionController::class, $resource.'_delete'])->name($resource.'.delete');
    }
});

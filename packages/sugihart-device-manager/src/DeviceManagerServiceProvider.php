<?php

namespace Sugihart\DeviceManager;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Sugihart\DeviceManager\Console\CreateAdmin;
use Sugihart\DeviceManager\Console\InstallDatabase;
use Sugihart\DeviceManager\Models\User;
use Sugihart\DeviceManager\Models\Vendor;

class DeviceManagerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sugihart-device-manager.php', 'sugihart-device-manager');
        $config = $this->app['config'];
        $config->set('database.connections.sdm', $config->get('sugihart-device-manager.database'));
        $config->set('auth.guards.sdm', ['driver' => 'session', 'provider' => 'sdm']);
        $config->set('auth.providers.sdm', ['driver' => 'eloquent', 'model' => User::class]);
        $config->set('auth.passwords.sdm', [
            'provider' => 'sdm', 'connection' => 'sdm',
            'table' => 'password_reset_tokens', 'expire' => 60, 'throttle' => 60,
        ]);
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'sdm');
        View::composer('sdm::layouts.app', function ($view): void {
            $view->with('vendor', Vendor::where('is_active', 1)->get());
        });
        if (! $this->app->routesAreCached()) {
            Route::prefix(trim(config('sugihart-device-manager.route_prefix'), '/'))
                ->name('sdm.')->group(__DIR__.'/../routes/api.php');
            Route::middleware('web')->prefix(trim(config('sugihart-device-manager.route_prefix'), '/'))
                ->name('sdm.')->group(__DIR__.'/../routes/web.php');
        }
        $this->publishes([
            __DIR__.'/../config/sugihart-device-manager.php' => config_path('sugihart-device-manager.php'),
        ], 'sdm-config');
        $this->publishes([
            __DIR__.'/../public' => public_path(trim(config('sugihart-device-manager.asset_prefix'), '/')),
        ], 'sdm-assets');
        if ($this->app->runningInConsole()) {
            $this->commands([InstallDatabase::class, CreateAdmin::class]);
        }
        // Migrations are explicit via sdm:install, never the host application's migrate.
    }
}

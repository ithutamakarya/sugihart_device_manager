<?php

namespace App\Providers;

use App\Models\Sugihart\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rule;

class SugihartServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $config = $this->app['config'];
        $config->set('database.connections.sdm', $config->get('sugihart-device-manager.database'));
        $config->set('auth.guards.sdm', ['driver' => 'session', 'provider' => 'sdm']);
        $config->set('auth.providers.sdm', ['driver' => 'eloquent', 'model' => User::class]);
        $config->set('auth.passwords.sdm', [
            'provider' => 'sdm', 'connection' => 'sdm', 'table' => 'password_reset_tokens', 'expire' => 60, 'throttle' => 60,
        ]);
    }

    public function boot(): void
    {
        if (! $this->app->routesAreCached()) {
            Route::prefix(trim(config('sugihart-device-manager.route_prefix'), '/'))->name('sdm.')
                ->group(base_path('routes/sugihart.php'));
        }
        if ($this->app->runningInConsole()) {
            Artisan::command('sdm:install {--force : Run on production}', function () {
                return $this->call('migrate', [
                    '--database' => 'sdm', '--path' => database_path('migrations/sugihart'),
                    '--realpath' => true, '--force' => $this->option('force'),
                ]);
            })->purpose('Create missing module tables without replacing imported data');
            Artisan::command('sdm:admin {email} {--name=Administrator}', function () {
                $data = ['email' => $this->argument('email'), 'name' => $this->option('name'), 'password' => $this->secret('Password (minimal 8 karakter)')];
                $validator = Validator::make($data, [
                    'email' => ['required', 'email', 'max:255', Rule::unique(User::class)],
                    'name' => 'required|string|max:255', 'password' => 'required|string|min:8',
                ]);
                if ($validator->fails()) {
                    foreach ($validator->errors()->all() as $error) {
                        $this->error($error);
                    }

                    return 1;
                }
                $data['password'] = Hash::make($data['password']);
                User::create($data);
                $this->info('Administrator device manager dibuat.');

                return 0;
            })->purpose('Create a module administrator using an interactive password');
        }
    }
}

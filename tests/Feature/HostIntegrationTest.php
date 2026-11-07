<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Sugihart\DeviceManager\Models\User;
use Tests\TestCase;

class HostUser extends Authenticatable
{
    protected $table = 'users';

    protected $guarded = [];
}

class HostIntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config([
            'auth.defaults.guard' => 'web',
            'auth.defaults.passwords' => 'users',
            'auth.guards.web' => ['driver' => 'session', 'provider' => 'host-users'],
            'auth.providers.host-users' => ['driver' => 'eloquent', 'model' => HostUser::class],
        ]);
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
        Route::get('/login', fn () => 'Host login')->name('login');
        Route::get('/', fn () => 'Host home')->name('home');
    }

    public function test_host_routes_guard_and_users_are_separate_from_device_manager(): void
    {
        $host = HostUser::create(['name' => 'Host account', 'email' => 'same@example.test', 'password' => Hash::make('host-password')]);
        User::create(['name' => 'SDM account', 'email' => $host->email, 'password' => Hash::make('sdm-password')]);
        $this->actingAs($host, 'web');
        $this->get('/')->assertContent('Host home');
        $this->get('/login')->assertContent('Host login');
        $this->get($this->path('main/vendor'))->assertRedirect(route('sdm.login'));
        $this->post($this->path('login'), ['email' => $host->email, 'password' => 'host-password'])->assertSessionHasErrors('email');
        $this->post($this->path('login'), ['email' => $host->email, 'password' => 'sdm-password'])->assertRedirect();
        $this->get($this->path('main/user_list'))->assertOk()->assertSee('SDM account')->assertDontSee('Host account');
        $this->assertSame('web', config('auth.defaults.guard'));
        $this->assertSame('users', config('auth.defaults.passwords'));
        $this->withSession(['host.preference' => 'keep-me'])->get($this->path('logout'))->assertRedirect(route('sdm.login'));
        $this->assertAuthenticatedAs($host, 'web');
        $this->assertGuest('sdm');
        $this->assertSame('keep-me', session('host.preference'));
        $this->artisan('sdm:install', ['--force' => true])->assertSuccessful();
        $this->assertSame('Host account', HostUser::firstOrFail()->name);
    }

    public function test_admin_mutations_require_csrf_while_legacy_api_is_stateless(): void
    {
        $admin = User::create(['name' => 'SDM', 'email' => 'sdm@example.test', 'password' => Hash::make('sdm-password')]);
        $this->actingAs($admin, 'sdm');
        // Enable the real CSRF check which Laravel normally skips under PHPUnit.
        $this->app['env'] = 'local';
        $this->post($this->path('main/create_vendor'), ['name' => 'No CSRF', 'token' => 'blocked'])->assertStatus(419);
        $this->getJson($this->path('api/get_kios/unknown'))->assertOk()->assertExactJson(['response' => 'failed']);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Sugihart\User;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class NativePersistenceTest extends TestCase
{
    public function test_rendered_login_and_admin_reference_existing_local_assets(): void
    {
        $pages = [$this->get($this->path('login'))->assertOk()->getContent()];
        $this->actingAs(User::create(['name' => 'Admin', 'email' => 'admin@example.test', 'password' => Hash::make('password')]), 'sdm');
        $pages[] = $this->get($this->path('main/user_list'))->assertOk()->getContent();
        foreach ($pages as $html) {
            preg_match_all('/(?:src|href)="([^"]+)"/', $html, $matches);
            foreach ($matches[1] as $url) {
                $path = parse_url($url, PHP_URL_PATH);
                if ($path && str_starts_with($path, '/vendor/sugihart-device-manager/')) {
                    $this->assertFileExists(public_path(ltrim($path, '/')), 'Missing rendered asset: '.$path);
                }
            }
        }
    }

    public function test_crud_preserves_timestamps_and_audit_atomicity(): void
    {
        $this->actingAs(User::create(['name' => 'Admin', 'email' => 'admin@example.test', 'password' => Hash::make('password')]), 'sdm');
        $this->post($this->path('main/create_vendor'), ['name' => 'Initial', 'token' => 'initial-token'])->assertRedirect();
        $vendor = DB::connection('sdm')->table('vendor')->first();
        $this->assertNotNull($vendor->created_at);
        $this->assertSame($vendor->created_at, $vendor->updated_at);
        $log = DB::connection('sdm')->table('log_activity')->first();
        $this->assertNotNull($log->created_at);
        $this->assertSame($vendor->created_at, $log->created_at);

        $this->withoutExceptionHandling();
        DB::listen(function (QueryExecuted $query): void {
            if ($query->connectionName === 'sdm' && str_contains($query->sql, 'insert into "log_activity"')) {
                throw new \RuntimeException('Simulated audit storage failure');
            }
        });
        try {
            $this->post($this->path('main/update_vendor'), ['id' => $vendor->id, 'name' => 'Should roll back', 'token' => 'changed-token']);
            $this->fail('Expected an audit failure');
        } catch (\RuntimeException $exception) {
            $this->assertSame('Simulated audit storage failure', $exception->getMessage());
        }
        $stored = DB::connection('sdm')->table('vendor')->first();
        $this->assertSame('Initial', $stored->name);
        $this->assertSame('initial-token', $stored->token);
        $this->assertSame(1, DB::connection('sdm')->table('log_activity')->count());
    }

    public function test_module_installer_does_not_execute_host_migrations(): void
    {
        $path = database_path('migrations/2099_01_01_000000_host_sentinel.php');
        file_put_contents($path, <<<'PHP'
<?php
return new class extends \Illuminate\Database\Migrations\Migration {
    public function up(): void {
        \Illuminate\Support\Facades\Schema::create('host_sentinel', function ($table) { $table->id(); });
    }
};
PHP);
        try {
            $this->artisan('sdm:install', ['--force' => true])->assertSuccessful();
            $this->assertFalse(DB::connection('sdm')->getSchemaBuilder()->hasTable('host_sentinel'));
            $this->assertFalse(DB::connection()->getSchemaBuilder()->hasTable('host_sentinel'));
            $this->assertSame(1, DB::connection('sdm')->table('migrations')->count());
        } finally {
            unlink($path);
        }
    }

    public function test_consolidated_device_screen_keeps_create_sub_and_edit_forms(): void
    {
        $this->actingAs(User::create(['name' => 'Admin', 'email' => 'admin@example.test', 'password' => Hash::make('password')]), 'sdm');
        $vendor = DB::connection('sdm')->table('vendor')->insertGetId(['name' => 'HK', 'token' => 'vendor-token']);
        DB::connection('sdm')->table('device')->insert(['name' => 'Camera', 'type' => 'cctv', 'vendor_id' => $vendor, 'token' => 'shared-token']);
        $this->get($this->path('main/device/'.$vendor))->assertOk()
            ->assertSee('Create Sub')->assertSee('create_sub', false)
            ->assertSee('main/update_device', false)->assertSee('name="_token"', false);
        $this->post($this->path('main/create_device'), ['name' => 'Sub', 'type' => 'cctv', 'vendor_id' => $vendor, 'token' => 'shared-token'])->assertRedirect();
        $this->assertSame(2, DB::connection('sdm')->table('device')->where('token', 'shared-token')->count());
    }
}

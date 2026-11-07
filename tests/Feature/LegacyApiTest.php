<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Sugihart\DeviceManager\Models\Device;
use Sugihart\DeviceManager\Models\Vendor;
use Tests\TestCase;

class LegacyApiTest extends TestCase
{
    private function device(array $data = []): Device
    {
        return Device::create($data + [
            'name' => 'Camera', 'url_device' => 'rtsp://192.168.1.2/stream/1',
            'type' => 'cctv', 'vendor_id' => 1, 'token' => 'camera-token',
            'is_active' => 1, 'ip_access' => '127.0.0.1',
        ]);
    }

    public function test_unknown_vendor_preserves_legacy_http_status_and_json(): void
    {
        foreach (['get_data_cctv', 'get_kios', 'get_device'] as $endpoint) {
            $response = $this->get($this->path('api/'.$endpoint.'/invalid'));
            $response->assertOk()->assertExactJson(['response' => 'failed']);
            $this->assertFalse($response->headers->has('Set-Cookie'));
        }
    }

    public function test_cctv_filters_orders_and_transforms_urls_without_changing_database(): void
    {
        Vendor::create(['id' => 1, 'name' => 'HK', 'token' => 'vendor-token']);
        $z = $this->device(['name' => 'Z', 'token' => 'b']);
        $a = $this->device(['name' => 'A', 'token' => 'a']);
        $b = $this->device(['name' => 'B', 'token' => 'a', 'url_device' => null]);
        $this->device(['is_active' => 0]);
        $this->device(['type' => 'kios-k']);
        $this->device(['vendor_id' => 2]);
        $response = $this->getJson($this->path('api/get_data_cctv/vendor-token'));
        $response->assertOk()->assertJsonCount(3)->assertJsonPath('0.id', $a->id)
            ->assertJsonPath('1.id', $b->id)->assertJsonPath('2.id', $z->id)
            ->assertJsonPath('0.url_device', 'rtsp:__192.168.1.2_stream_1')
            ->assertJsonPath('1.url_device', '');
        $this->assertSame('rtsp://192.168.1.2/stream/1', $a->fresh()->url_device);
        $this->assertSame($a->getRawOriginal('created_at'), $response->json('0.created_at'));
        $this->assertEqualsCanonicalizing([
            'id', 'name', 'url_device', 'type', 'vendor_id', 'ip_access', 'is_active', 'token', 'created_at', 'updated_at',
        ], array_keys($response->json('0')));
    }

    public function test_kios_and_all_devices_preserve_different_active_filters(): void
    {
        // Vendor activity was never filtered by the legacy API.
        Vendor::create(['id' => 1, 'name' => 'HK', 'token' => 'vendor-token', 'is_active' => 0]);
        $this->device();
        $kios = $this->device(['type' => 'kios-k', 'token' => 'kios-a']);
        $this->device(['type' => 'kios-k', 'is_active' => 0]);
        $this->device(['vendor_id' => 2]);
        $this->getJson($this->path('api/get_kios/vendor-token'))->assertOk()->assertJsonCount(1)
            ->assertJsonPath('0.id', $kios->id)->assertJsonPath('0.url_device', $kios->url_device);
        $this->getJson($this->path('api/get_device/vendor-token'))->assertOk()->assertJsonCount(3);
    }

    public function test_null_tokens_keep_the_mysql_sort_order_on_postgresql(): void
    {
        Vendor::create(['name' => 'HK', 'token' => 'vendor-token']);
        foreach (['cctv' => 'get_data_cctv', 'kios-k' => 'get_kios'] as $type => $endpoint) {
            $withToken = $this->device(['type' => $type, 'token' => 'a']);
            $withoutToken = $this->device(['type' => $type, 'token' => null]);
            $this->getJson($this->path('api/'.$endpoint.'/vendor-token'))->assertOk()
                ->assertJsonPath('0.id', $withoutToken->id)->assertJsonPath('1.id', $withToken->id);
        }
    }

    public function test_vendor_without_devices_returns_an_empty_array(): void
    {
        Vendor::create(['name' => 'Empty', 'token' => 'empty']);
        foreach (['get_data_cctv', 'get_kios', 'get_device'] as $endpoint) {
            $this->getJson($this->path('api/'.$endpoint.'/empty'))->assertOk()->assertExactJson([]);
        }
    }

    public function test_kiosk_render_uses_the_exact_legacy_path_and_configured_services(): void
    {
        $this->device(['type' => 'kios-k', 'token' => 'legacy-kios-token', 'ip_access' => '10.0.0.5']);
        config([
            'sugihart-device-manager.attendance_url' => 'https://attendance.example.test/',
            'sugihart-device-manager.kiosk_local_url' => 'http://localhost/local-kios/',
            'sugihart-device-manager.kiosk_asset_url' => 'http://localhost/local-assets/',
        ]);
        $response = $this->get($this->path('api/get_render_kios/legacy-kios-token'));
        $response->assertOk()->assertSee('const token_device =', false)
            ->assertSee('legacy-kios-token')->assertSee('attendance.example.test', false)
            ->assertSee('local-kios', false)
            ->assertSee('http://localhost/local-assets/public/css/bootstrap.min.css', false)
            ->assertSee('kiosk_asset_url + "public/image/capture.jpg?rand="', false);
        $this->assertStringContainsString('text/html', $response->headers->get('Content-Type'));
        $this->assertFalse($response->headers->has('Set-Cookie'));
    }

    public function test_invalid_inactive_and_non_kiosk_tokens_return_legacy_404_body(): void
    {
        $this->device(['token' => 'camera']);
        $this->device(['type' => 'kios-k', 'token' => 'inactive', 'is_active' => 0]);
        foreach (['missing', 'camera', 'inactive'] as $token) {
            $this->get($this->path('api/get_render_kios/'.$token))->assertOk()->assertContent('404');
        }
    }

    public function test_optional_ip_check_uses_the_framework_request_ip(): void
    {
        $this->device(['type' => 'kios-k', 'token' => 'restricted', 'ip_access' => '10.0.0.5']);
        config(['sugihart-device-manager.enforce_kiosk_ip' => true]);
        $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.1'])
            ->get($this->path('api/get_render_kios/restricted'))->assertContent('404');
        $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.5'])
            ->get($this->path('api/get_render_kios/restricted'))->assertSee('ATTENDANCE LIST');
    }

    public function test_rerunning_install_preserves_existing_tables_tokens_and_passwords(): void
    {
        DB::connection('sdm')->table('users')->insert(['name' => 'Old admin', 'email' => 'old@example.test', 'password' => 'existing-hash']);
        $device = $this->device();
        $this->artisan('sdm:install', ['--force' => true])->assertSuccessful();
        $this->artisan('sdm:install', ['--force' => true])->assertSuccessful();
        $this->assertSame($device->token, $device->fresh()->token);
        $this->assertSame('existing-hash', DB::connection('sdm')->table('users')->value('password'));
    }
}

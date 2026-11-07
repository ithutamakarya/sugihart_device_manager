<?php

namespace Sugihart\DeviceManager\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function getDeviceCCTV(string $token): JsonResponse
    {
        return $this->devices($token, 'cctv');
    }

    public function getDeviceKios(string $token): JsonResponse
    {
        return $this->devices($token, 'kios-k');
    }

    public function getDevice(string $token): JsonResponse
    {
        return $this->devices($token);
    }

    private function devices(string $token, ?string $type = null): JsonResponse
    {
        $db = DB::connection('sdm');
        $vendor = $db->table('vendor')->where('token', $token)->first();
        if (! $vendor) {
            // Existing clients expect HTTP 200 for an unknown vendor token.
            return response()->json(['response' => 'failed'], 200);
        }
        $query = $db->table('device')->where('vendor_id', $vendor->id);
        if ($type !== null) {
            $query->where('type', $type)->where('is_active', 1)
                // PostgreSQL places NULL last by default; keep the legacy NULL-first order.
                ->orderByRaw('token IS NOT NULL')->orderBy('token')->orderBy('name');
        }
        $devices = $query->get();
        if ($type === 'cctv') {
            foreach ($devices as $device) {
                $device->url_device = str_replace('/', '_', $device->url_device ?? '');
            }
        }

        // Query builder preserves the original fields and raw timestamp strings.
        return response()->json($devices, 200);
    }

    public function render_kios(Request $request, string $token = '')
    {
        $query = DB::connection('sdm')->table('device')
            ->where('type', 'kios-k')->where('is_active', 1)->where('token', $token);
        if (config('sugihart-device-manager.enforce_kiosk_ip')) {
            $query->where('ip_access', $request->ip());
        }
        if ($token === '' || ! $query->exists()) {
            return response('404', 200);
        }
        $pc_local = rtrim(config('sugihart-device-manager.kiosk_asset_url'), '/').'/';

        return response()->view('sdm::render_kios.index', compact('token', 'pc_local'));
    }
}

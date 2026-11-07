<?php

namespace Sugihart\DeviceManager\Http\Controllers;

use Sugihart\DeviceManager\Models\Device;
use Sugihart\DeviceManager\Models\LogActivity;
use Sugihart\DeviceManager\Models\User;
use Sugihart\DeviceManager\Models\Vendor;

class MainController extends Controller
{
    public function index()
    {
        return view('sdm::home');
    }

    public function device(int $vendor_id)
    {
        $vendor = Vendor::findOrFail($vendor_id);
        $query = Device::where('vendor_id', $vendor_id)->where('is_active', 1)
            ->orderByRaw('token IS NOT NULL')->orderBy('token')->orderBy('name');

        return view('sdm::main.device.index', [
            'vendor_id' => $vendor_id, 'vendor_name' => $vendor->name,
            'device_cctv' => (clone $query)->where('type', 'cctv')->get(),
            'device_kios' => (clone $query)->where('type', 'kios-k')->get(),
        ]);
    }

    public function users()
    {
        return view('sdm::main.user.index', ['users' => User::where('is_active', 1)->get()]);
    }

    public function vendor()
    {
        return view('sdm::main.vendor.index', ['vendor' => Vendor::where('is_active', 1)->get()]);
    }

    public function log_activity()
    {
        return view('sdm::main.log_activity.index', ['log_activity' => LogActivity::orderByDesc('id')->get()]);
    }
}

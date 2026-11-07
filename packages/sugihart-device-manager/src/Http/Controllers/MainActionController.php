<?php

namespace Sugihart\DeviceManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Sugihart\DeviceManager\Models\Device;
use Sugihart\DeviceManager\Models\User;
use Sugihart\DeviceManager\Models\Vendor;
use Sugihart\DeviceManager\Support\Urls;

class MainActionController extends BaseCoreController
{
    public function user_create(Request $request)
    {
        $data = $this->userData($request);
        $data['password'] = Hash::make($data['password']);
        $this->saveWithLog(new User, $data, 'Simpan', 'User');

        return redirect()->to(Urls::page('main/user_list'));
    }

    public function user_update(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        $user = User::findOrFail($request->integer('id'));
        $data = $this->userData($request, $user);
        if ($request->filled('password') && $request->password !== 'terlalu') {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $this->saveWithLog($user, $data, 'Ubah', 'User');

        return redirect()->to(Urls::page('main/user_list'));
    }

    public function user_delete(int $id)
    {
        $this->saveWithLog(User::findOrFail($id), ['is_active' => 0], 'Hapus', 'User');

        return redirect()->to(Urls::page('main/user_list'));
    }

    private function userData(Request $request, ?User $user = null): array
    {
        $passwordRules = $user && (! $request->filled('password') || $request->password === 'terlalu')
            ? 'nullable|string' : 'required|string|min:8';

        return $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($user?->id)],
            'password' => $passwordRules,
        ]);
    }

    public function vendor_create(Request $request)
    {
        $this->saveWithLog(new Vendor, $this->vendorData($request), 'Simpan', 'Vendor');

        return redirect()->to(Urls::page('main/vendor'));
    }

    public function vendor_update(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        $vendor = Vendor::findOrFail($request->integer('id'));
        $this->saveWithLog($vendor, $this->vendorData($request, $vendor), 'Ubah', 'Vendor');

        return redirect()->to(Urls::page('main/vendor'));
    }

    public function vendor_delete(int $id)
    {
        $this->saveWithLog(Vendor::findOrFail($id), ['is_active' => 0], 'Hapus', 'Vendor');

        return redirect()->to(Urls::page('main/vendor'));
    }

    private function vendorData(Request $request, ?Vendor $vendor = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'token' => ['required', 'string', 'max:255', Rule::unique(Vendor::class)->ignore($vendor?->id)],
        ]);
    }

    public function device_create(Request $request)
    {
        $data = $this->deviceData($request);
        $this->saveWithLog(new Device, $data, 'Simpan', 'Device');

        return redirect()->to(Urls::page('main/device/'.$data['vendor_id']));
    }

    public function device_update(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        $data = $this->deviceData($request);
        $this->saveWithLog(Device::findOrFail($request->integer('id')), $data, 'Ubah', 'Device');

        return redirect()->to(Urls::page('main/device/'.$data['vendor_id']));
    }

    public function device_delete(int $id, int $vendor_id)
    {
        $device = Device::where('vendor_id', $vendor_id)->findOrFail($id);
        $this->saveWithLog($device, ['is_active' => 0], 'Hapus', 'Device');

        return redirect()->to(Urls::page('main/device/'.$vendor_id));
    }

    private function deviceData(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'url_device' => 'nullable|string|max:255',
            'type' => ['required', Rule::in(['cctv', 'kios-k'])],
            'vendor_id' => ['required', 'integer', Rule::exists(Vendor::class, 'id')->where('is_active', 1)],
            'ip_access' => 'nullable|string|max:255',
            // CCTV subdevices deliberately share tokens in the original application.
            'token' => 'nullable|string|max:255',
        ]) + ['url_device' => null, 'ip_access' => null, 'token' => null];
    }
}

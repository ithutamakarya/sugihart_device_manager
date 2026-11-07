<?php

namespace App\Http\Controllers\Sugihart;

use App\Models\Sugihart\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class DeviceManagerController extends Controller
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

        return response()->view('sugihart.kios', compact('token', 'pc_local'));
    }

    public function loginForm()
    {
        return auth('sdm')->check() ? redirect()->route('sdm.home') : view('sugihart.admin', ['screen' => 'login']);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate(['email' => 'required|email', 'password' => 'required|string']);
        $key = 'sdm:login:'.sha1(Str::lower($credentials['email']).'|'.$request->ip());
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages(['email' => 'Terlalu banyak percobaan. Coba lagi dalam '.RateLimiter::availableIn($key).' detik.']);
        }
        if (! auth('sdm')->attempt($credentials + ['is_active' => 1], $request->boolean('remember'))) {
            RateLimiter::hit($key, 60);
            throw ValidationException::withMessages(['email' => __('auth.failed')]);
        }
        RateLimiter::clear($key);
        $request->session()->regenerate();

        return redirect()->to($request->session()->pull('sdm.url.intended', route('sdm.home')));
    }

    public function logout(Request $request)
    {
        auth('sdm')->logout();
        $request->session()->forget('sdm.url.intended');
        $request->session()->regenerateToken();

        return redirect()->route('sdm.login');
    }

    public function registerForm()
    {
        abort_unless(config('sugihart-device-manager.registration_enabled'), 404);

        return view('sugihart.admin', ['screen' => 'register']);
    }

    public function register(Request $request)
    {
        abort_unless(config('sugihart-device-manager.registration_enabled'), 404);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)],
            'password' => 'required|string|min:8|confirmed',
        ]);
        $data['password'] = Hash::make($data['password']);
        auth('sdm')->login(User::create($data));
        $request->session()->regenerate();

        return redirect()->route('sdm.home');
    }

    public function passwordRequest()
    {
        return view('sugihart.admin', ['screen' => 'email']);
    }

    public function passwordEmail(Request $request)
    {
        $data = $request->validate(['email' => 'required|email']);
        $status = Password::broker('sdm')->sendResetLink($data + ['is_active' => 1]);

        return $status === Password::ResetLinkSent
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)])->onlyInput('email');
    }

    public function passwordForm(Request $request, string $token)
    {
        return view('sugihart.admin', ['screen' => 'reset', 'token' => $token, 'email' => $request->query('email')]);
    }

    public function passwordReset(Request $request)
    {
        $data = $request->validate([
            'token' => 'required|string', 'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $status = Password::broker('sdm')->reset($data + ['is_active' => 1], function (User $user, string $password): void {
            $user->forceFill(['password' => Hash::make($password), 'remember_token' => Str::random(60)])->save();
            event(new PasswordReset($user));
        });

        return $status === Password::PasswordReset
            ? redirect()->route('sdm.login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)])->onlyInput('email');
    }

    public static function pageUrl(string $path = ''): string
    {
        return url(trim(config('sugihart-device-manager.route_prefix'), '/').'/'.ltrim($path, '/'));
    }

    public static function assetUrl(string $path = ''): string
    {
        return asset(trim(config('sugihart-device-manager.asset_prefix'), '/').'/'.ltrim($path, '/'));
    }

    private function table(string $table): Builder
    {
        return DB::connection('sdm')->table($table);
    }

    private function record(string $table, int $id): object
    {
        $record = $this->table($table)->where('id', $id)->first();
        abort_unless($record, 404);

        return $record;
    }

    private function adminPage(string $page, array $data = [])
    {
        return view('sugihart.admin', $data + [
            'screen' => 'admin', 'page' => $page,
            'vendor' => $this->table('vendor')->where('is_active', 1)->get(),
        ]);
    }

    public function index()
    {
        return $this->adminPage('home');
    }

    public function users()
    {
        return $this->adminPage('user', ['users' => $this->table('users')->where('is_active', 1)->get()]);
    }

    public function vendor()
    {
        return $this->adminPage('vendor');
    }

    public function log_activity()
    {
        return $this->adminPage('log', ['log_activity' => $this->table('log_activity')->orderByDesc('id')->get()]);
    }

    public function device(int $vendor_id)
    {
        $vendor = $this->record('vendor', $vendor_id);
        $query = $this->table('device')->where('vendor_id', $vendor_id)->where('is_active', 1)
            ->orderByRaw('token IS NOT NULL')->orderBy('token')->orderBy('name');

        return $this->adminPage('device', [
            'vendor_id' => $vendor_id, 'vendor_name' => $vendor->name,
            'device_cctv' => (clone $query)->where('type', 'cctv')->get(),
            'device_kios' => (clone $query)->where('type', 'kios-k')->get(),
        ]);
    }

    private function saveWithLog(string $table, ?int $id, array $data, string $action, string $resource): void
    {
        DB::connection('sdm')->transaction(function () use ($table, $id, $data, $action, $resource): void {
            $timestamp = now()->toDateTimeString();
            $data['updated_at'] = $timestamp;
            if ($id === null) {
                $data['created_at'] = $timestamp;
                $this->table($table)->insert($data);
            } else {
                $this->record($table, $id);
                $this->table($table)->where('id', $id)->update($data);
            }
            $this->table('log_activity')->insert([
                'user_device' => auth('sdm')->user()->name,
                'activity' => 'Melakukan '.$action.' Data '.$resource,
                'ip_address' => request()->ip() ?? 'UNKNOWN',
                'created_at' => $timestamp, 'updated_at' => $timestamp,
            ]);
        });
        session()->flash('sdm.message_alert', 'Data Berhasil Di '.$action);
        session()->flash('sdm.status_alert', 'success');
    }

    public function user_create(Request $request)
    {
        return $this->saveUser($request);
    }

    public function user_update(Request $request)
    {
        return $this->saveUser($request, true);
    }

    private function saveUser(Request $request, bool $update = false)
    {
        $id = null;
        if ($update) {
            $request->validate(['id' => 'required|integer']);
            $id = $request->integer('id');
            $this->record('users', $id);
        }
        $keepPassword = $update && (! $request->filled('password') || $request->password === 'terlalu');
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('sdm.users')->ignore($id)],
            'password' => $keepPassword ? 'nullable|string' : 'required|string|min:8',
        ]);
        if ($keepPassword) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }
        $this->saveWithLog('users', $id, $data, $update ? 'Ubah' : 'Simpan', 'User');

        return redirect()->to(self::pageUrl('main/user_list'));
    }

    public function user_delete(int $id)
    {
        $this->saveWithLog('users', $id, ['is_active' => 0], 'Hapus', 'User');

        return redirect()->to(self::pageUrl('main/user_list'));
    }

    public function vendor_create(Request $request)
    {
        return $this->saveVendor($request);
    }

    public function vendor_update(Request $request)
    {
        return $this->saveVendor($request, true);
    }

    private function saveVendor(Request $request, bool $update = false)
    {
        $id = null;
        if ($update) {
            $request->validate(['id' => 'required|integer']);
            $id = $request->integer('id');
            $this->record('vendor', $id);
        }
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'token' => ['required', 'string', 'max:255', Rule::unique('sdm.vendor', 'token')->ignore($id)],
        ]);
        $this->saveWithLog('vendor', $id, $data, $update ? 'Ubah' : 'Simpan', 'Vendor');

        return redirect()->to(self::pageUrl('main/vendor'));
    }

    public function vendor_delete(int $id)
    {
        $this->saveWithLog('vendor', $id, ['is_active' => 0], 'Hapus', 'Vendor');

        return redirect()->to(self::pageUrl('main/vendor'));
    }

    public function device_create(Request $request)
    {
        return $this->saveDevice($request);
    }

    public function device_update(Request $request)
    {
        return $this->saveDevice($request, true);
    }

    private function saveDevice(Request $request, bool $update = false)
    {
        $id = null;
        if ($update) {
            $request->validate(['id' => 'required|integer']);
            $id = $request->integer('id');
            $this->record('device', $id);
        }
        $data = $request->validate([
            'name' => 'required|string|max:255', 'url_device' => 'nullable|string|max:255',
            'type' => ['required', Rule::in(['cctv', 'kios-k'])],
            'vendor_id' => ['required', 'integer', Rule::exists('sdm.vendor', 'id')->where('is_active', 1)],
            'ip_access' => 'nullable|string|max:255', 'token' => 'nullable|string|max:255',
        ]) + ['url_device' => null, 'ip_access' => null, 'token' => null];
        $this->saveWithLog('device', $id, $data, $update ? 'Ubah' : 'Simpan', 'Device');

        return redirect()->to(self::pageUrl('main/device/'.$data['vendor_id']));
    }

    public function device_delete(int $id, int $vendor_id)
    {
        $device = $this->record('device', $id);
        abort_unless((int) $device->vendor_id === $vendor_id, 404);
        $this->saveWithLog('device', $id, ['is_active' => 0], 'Hapus', 'Device');

        return redirect()->to(self::pageUrl('main/device/'.$vendor_id));
    }
}

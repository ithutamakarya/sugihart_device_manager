<?php

namespace Sugihart\DeviceManager\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Sugihart\DeviceManager\Models\User;

class AuthController extends Controller
{
    public function loginForm()
    {
        return auth('sdm')->check() ? redirect()->route('sdm.home') : view('sdm::auth.login');
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

        return view('sdm::auth.register');
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
        return view('sdm::auth.passwords.email');
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
        return view('sdm::auth.passwords.reset', ['token' => $token, 'email' => $request->query('email')]);
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
}

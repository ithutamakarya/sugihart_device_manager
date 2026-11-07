<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Sugihart\DeviceManager\Models\Device;
use Sugihart\DeviceManager\Models\LogActivity;
use Sugihart\DeviceManager\Models\User;
use Sugihart\DeviceManager\Models\Vendor;
use Sugihart\DeviceManager\Notifications\ResetPassword;
use Tests\TestCase;

class AdminTest extends TestCase
{
    private function admin(array $data = []): User
    {
        return User::create($data + ['name' => 'Administrator', 'email' => 'admin@example.test', 'password' => Hash::make('old-password'), 'is_active' => 1]);
    }

    public function test_guest_is_redirected_to_package_login(): void
    {
        $this->get($this->path('main/vendor'))->assertRedirect(route('sdm.login'));
        $this->get($this->path('login'))->assertOk()->assertSee(route('sdm.login'), false);
        $this->get($this->path('password/reset'))->assertOk();
        $this->get($this->path('password/reset/example-token'))->assertOk();
    }

    public function test_legacy_passwords_work_and_login_returns_to_requested_module_page(): void
    {
        $user = $this->admin();
        $this->get($this->path('main/vendor'));
        $this->post($this->path('login'), ['email' => $user->email, 'password' => 'old-password'])
            ->assertRedirect(url($this->path('main/vendor')));
        $this->assertAuthenticatedAs($user, 'sdm');
        foreach (['', 'main/user_list', 'main/vendor', 'main/log_activity'] as $page) {
            $this->get($this->path($page))->assertOk();
        }
    }

    public function test_inactive_accounts_cannot_login_or_keep_using_existing_sessions(): void
    {
        $user = $this->admin(['is_active' => 0]);
        $this->post($this->path('login'), ['email' => $user->email, 'password' => 'old-password'])->assertSessionHasErrors('email');
        $this->assertGuest('sdm');
        $this->actingAs($user, 'sdm')->get($this->path('main/vendor'))->assertRedirect(route('sdm.login'));
    }

    public function test_login_is_throttled_after_five_failures(): void
    {
        $user = $this->admin();
        for ($i = 0; $i < 5; $i++) {
            $this->post($this->path('login'), ['email' => $user->email, 'password' => 'wrong'])->assertSessionHasErrors('email');
        }
        $this->post($this->path('login'), ['email' => $user->email, 'password' => 'old-password'])->assertSessionHasErrors('email');
        $this->assertGuest('sdm');
    }

    public function test_admin_can_manage_vendor_and_device_and_write_audit_logs(): void
    {
        $this->actingAs($this->admin(), 'sdm');
        $this->post($this->path('main/create_vendor'), ['name' => 'HK', 'token' => 'vendor-original'])->assertRedirect();
        $vendor = Vendor::firstOrFail();
        $this->post($this->path('main/update_vendor'), ['id' => $vendor->id, 'name' => 'HK updated', 'token' => 'vendor-original'])->assertRedirect();
        $data = ['vendor_id' => $vendor->id, 'name' => 'Kios', 'type' => 'kios-k', 'token' => 'device-original'];
        $this->post($this->path('main/create_device'), $data)->assertRedirect();
        $device = Device::firstOrFail();
        $this->get($this->path('main/device/'.$vendor->id))->assertOk()->assertSee('HK updated');
        $this->post($this->path('main/update_device'), ['id' => $device->id, 'name' => 'Kios updated'] + $data)->assertRedirect();
        $this->assertSame('device-original', $device->fresh()->token);
        $this->post($this->path('main/delete_device/'.$device->id.'/'.$vendor->id))->assertRedirect();
        $this->assertSame(0, $device->fresh()->is_active);
        $this->post($this->path('main/delete_vendor/'.$vendor->id))->assertRedirect();
        $this->assertSame(0, $vendor->fresh()->is_active);
        $this->assertSame(6, LogActivity::count());
    }

    public function test_user_update_keeps_password_for_legacy_placeholder_and_can_replace_password(): void
    {
        $this->actingAs($this->admin(), 'sdm');
        $this->post($this->path('main/create_user'), ['name' => 'Operator', 'email' => 'operator@example.test', 'password' => 'initial-password'])->assertRedirect();
        $user = User::where('email', 'operator@example.test')->firstOrFail();
        $data = ['id' => $user->id, 'name' => 'Operator updated', 'email' => $user->email, 'password' => 'terlalu'];
        $this->post($this->path('main/update_user'), $data)->assertRedirect();
        $this->assertTrue(Hash::check('initial-password', $user->fresh()->password));
        $data['password'] = 'new-password';
        $this->post($this->path('main/update_user'), $data)->assertRedirect();
        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
        $this->post($this->path('main/delete_user/'.$user->id))->assertRedirect();
        $this->assertSame(0, $user->fresh()->is_active);
    }

    public function test_validation_prevents_partial_records_and_logs(): void
    {
        $this->actingAs($this->admin(), 'sdm');
        $this->post($this->path('main/create_device'), ['name' => 'Invalid', 'type' => 'unknown', 'vendor_id' => 999])
            ->assertSessionHasErrors(['type', 'vendor_id']);
        $this->assertSame(0, Device::count());
        $this->assertSame(0, LogActivity::count());
        $this->get($this->path('main/device/999'))->assertNotFound();
        $this->get($this->path('main/delete_user/1'))->assertStatus(405);
    }

    public function test_password_reset_uses_module_notification_and_broker(): void
    {
        Notification::fake();
        $user = $this->admin();
        $this->post($this->path('password/email'), ['email' => $user->email])->assertSessionHas('status');
        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user): bool {
            $this->assertStringContainsString('/sugihart_device_manager/public/password/reset/', $notification->toMail($user)->actionUrl);

            return true;
        });
        $token = Password::broker('sdm')->createToken($user);
        $this->post($this->path('password/reset'), [
            'token' => $token, 'email' => $user->email, 'password' => 'new-password', 'password_confirmation' => 'new-password',
        ])->assertRedirect(route('sdm.login'));
        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }

    public function test_public_registration_requires_explicit_configuration(): void
    {
        $this->get($this->path('register'))->assertNotFound();
        config(['sugihart-device-manager.registration_enabled' => true]);
        $this->get($this->path('register'))->assertOk();
        $this->post($this->path('register'), [
            'name' => 'New admin', 'email' => 'new@example.test', 'password' => 'new-password', 'password_confirmation' => 'new-password',
        ])->assertRedirect(route('sdm.home'));
        $this->assertAuthenticated('sdm');
    }
}

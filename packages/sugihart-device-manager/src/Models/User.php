<?php

namespace Sugihart\DeviceManager\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Sugihart\DeviceManager\Notifications\ResetPassword;

class User extends Authenticatable
{
    use Notifiable;

    protected $connection = 'sdm';

    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['email_verified_at' => 'datetime'];

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPassword($token));
    }
}

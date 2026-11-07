<?php

namespace App\Models\Sugihart;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $connection = 'sdm';

    protected $table = 'users';

    protected $attributes = ['is_active' => 1];

    protected $fillable = ['name', 'email', 'password', 'is_active'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['email_verified_at' => 'datetime'];

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new class($token) extends ResetPassword
        {
            public function toMail($notifiable): MailMessage
            {
                $url = route('sdm.password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()]);

                return (new MailMessage)
                    ->subject('Reset Password Sugihart Device Manager')
                    ->line('Anda menerima email ini karena ada permintaan reset password akun device manager.')
                    ->action('Reset Password', $url)
                    ->line('Tautan berlaku 60 menit. Abaikan jika Anda tidak meminta reset password.');
            }
        });
    }
}

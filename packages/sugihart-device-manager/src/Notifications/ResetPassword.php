<?php

namespace Sugihart\DeviceManager\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends BaseResetPassword
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
}

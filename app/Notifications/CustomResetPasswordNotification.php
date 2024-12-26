<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;

class CustomResetPasswordNotification extends ResetPassword
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $app_url_front = env('APP_URL_FRONT', 'http://localhost:5173');
        $url = url($app_url_front.'/reset-password?token=' . $this->token . '&email=' . $notifiable->email);

        return (new MailMessage)
                    ->subject('Restablecimiento de Contraseña')
                    ->line('Has recibido este correo porque hemos recibido una solicitud de restablecimiento de contraseña para tu cuenta.')
                    ->action('Restablecer Contraseña', $url)
                    ->line('Si no solicitaste un restablecimiento de contraseña, no se requiere ninguna acción adicional.');
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function ($notifiable, $url) {

            return (new MailMessage)
                ->subject('Verifica tu cuenta - Mi Estantería')
                ->greeting('¡Hola '.$notifiable->name.'!')
                ->line('Gracias por registrarte en Mi Estantería.')
                ->line('Para activar tu cuenta y comenzar a usar la plataforma, confirma tu correo electrónico.')
                ->action('Verificar mi cuenta', $url)
                ->line('Si no creaste esta cuenta, puedes ignorar este mensaje.')
                ->salutation('Saludos, Equipo Mi Estantería');
        });
    }
}

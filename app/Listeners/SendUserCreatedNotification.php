<?php

namespace App\Listeners;

use App\Events\UserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendUserCreatedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        // Aquí puedes enviar un email, notificación, etc.
        Log::info('Nuevo usuario creado', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'name' => $event->user->name,
        ]);

        // Ejemplo: enviar email de bienvenida
        // Mail::to($event->user->email)->send(new WelcomeEmail($event->user));
    }
}

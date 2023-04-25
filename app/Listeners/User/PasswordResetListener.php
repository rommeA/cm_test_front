<?php

namespace App\Listeners\User;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class PasswordResetListener
{
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
     * @param  PasswordReset  $event
     * @return void
     */
    public function handle(PasswordReset $event)
    {
        $event->user->unblock();
    }
}

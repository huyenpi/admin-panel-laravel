<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Support\Facades\Request;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Authenticated $event): void
    {
        //Get the authenticated user
        $user = $event->user;

        //Save user's IP address and login time to the database
        $user->update([
            'last_login_ip' => Request::ip(),
            'last_login_time' => now()
        ]);

    }
}

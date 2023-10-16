<?php

namespace App\Listeners;

use App\Events\Created;
use App\Notifications\CreatedEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailCreateNotification
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
    public function handle(Created $event): void
    {
        $data = $event->data;

       $user = $data->workspace->users('owner')->first();

       $user->notify(new CreatedEmail($data));

    }
}

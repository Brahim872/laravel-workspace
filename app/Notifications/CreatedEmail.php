<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CreatedEmail extends Notification
{
    use Queueable;

    public $data;
    public $created;

    /**
     * Create a new notification instance.
     * @param $data
     * @param string $created
     */
    public function __construct($data,$created = "app")
    {
        $this->data = $data;
        $this->created = $created;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     * @param object $notifiable
     * @return MailMessage
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Create new '. $this->created)
                    ->greeting('Hi greeting')
                    ->line('You just Create a new application')
                    ->line('Application name : '.$this->data->name)
                    ->line('By : ' . returnUserApi()->name)
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}

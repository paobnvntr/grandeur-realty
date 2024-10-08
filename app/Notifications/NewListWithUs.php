<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewListWithUs extends Notification
{
    use Queueable;

    protected $listWithUs;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($listWithUs)
    {
        $this->listWithUs = $listWithUs;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'id' => $this->listWithUs['id'],
            'name' => $this->listWithUs['name']
        ];
    }
}

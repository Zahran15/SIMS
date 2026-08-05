<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NotifPelanggan extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected $icon;

    public function __construct($title, $message, $icon = 'fa-solid fa-laptop-medical')
    {
        $this->title = $title;
        $this->message = $message;
        $this->icon = $icon;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title'   => $this->title,
            'message' => $this->message,
            'icon'    => $this->icon,
        ];
    }
}
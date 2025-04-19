<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Event;
use NotificationChannels\Fcm\FcmMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;


class MobileEventReminder extends Notification
{
    use Queueable;

    private $event;
    public function __construct(Event $event) { $this->event = $event; }

    public function via($notifiable) {
        return ['fcm'];
    }

    public function toFcm($notifiable) {
        return FcmMessage::create()
            ->setData([
               'event_id'   => $this->event->id,
               'title'      => $this->event->title,
               'event_time' => (string)$this->event->event_time,
            ])
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
               ->setTitle('Upcoming: '.$this->event->title)
               ->setBody('Starts at '.$this->event->event_time)
            );
    }
}

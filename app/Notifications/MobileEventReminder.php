<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmMessage;
use Illuminate\Support\Facades\Event;


class MobileEventReminder extends Notification
{
    use Queueable;

    protected $Name;
    protected $lat;
    protected $long;
    protected $address;

    /**
     * Create a new notification instance.
     *
     * @param string $Name
     * @param string $lat
     * @param string $long
     * @param string $address
     */
    public function __construct($Name, $lat, $long, $address) {
        $this->Name  = $Name;
        $this->lat         = $lat;
        $this->long        = $long;
        $this->address     = $address;
    }

    /**
     * Get the notification’s delivery channels.
     */
    public function via($notifiable) {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable) {
        return [
            'name'  => $this->Name,
            'latitude'     => $this->lat,
            'longitude'    => $this->long,
            'address'      => $this->address,
            'message'      => "Emergency! {$this->Name} is in trouble. Their current location is {$this->address}",
        ];
    }

    /**
     * Data for push notification payload.
     */
    public function toPushNotification($notifiable) {
        return [
            'title' => 'Emergency Alert!',
            'body'  => "{$this->Name}) needs help at {$this->address}. Coordinates: ({$this->lat}, {$this->long})",
        ];
    }
}

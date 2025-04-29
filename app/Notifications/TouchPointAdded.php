<?php

namespace App\Notifications;

use App\Helpers\PushNotificationHelper;
use App\Models\TouchPoint;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class TouchPointAdded extends Notification {
    use Queueable;

    public function __construct(protected TouchPoint $tp) {}

    public function via($notifiable): array {
        return ['database'];
    }

    public function toDatabase($notifiable): DatabaseMessage {
        $userId = $notifiable->id;
        $title  = 'Touchpoint Added';
        $body   = "{$this->tp->name} added successfully";

        // Log before push
        Log::info("[Notification] TouchPointAdded: notifying user {$userId}", ['touch_point' => $this->tp->id]);
        PushNotificationHelper::sendPushNotification($userId, $title, $body);
        Log::info("[Notification] TouchPointAdded: database record for user {$userId}");

        return new DatabaseMessage([
            'title'   => $title,
            'message' => $body,
            'type'    => 'touch_point_added',
            'data'    => ['touch_point_id' => $this->tp->id],
        ]);
    }
}

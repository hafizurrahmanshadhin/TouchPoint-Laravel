<?php

namespace App\Notifications;

use App\Helpers\PushNotificationHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class BadgeEarned extends Notification {
    use Queueable;

    public function __construct(protected string $badge) {}

    public function via($notifiable): array {
        return ['database'];
    }

    public function toDatabase($notifiable): DatabaseMessage {
        $userId = $notifiable->id;
        $title  = 'Reward Earned';
        $body   = "You earned a {$this->badge} Badge!";

        // Log push send
        Log::info("[Notification] BadgeEarned: notifying user {$userId}", ['badge' => $this->badge]);
        PushNotificationHelper::sendPushNotification($userId, $title, $body);
        Log::info("[Notification] BadgeEarned: database record for user {$userId}");

        return new DatabaseMessage([
            'title'   => $title,
            'message' => $body,
            'type'    => 'badge_earned',
            'data'    => ['badge' => $this->badge],
        ]);
    }
}

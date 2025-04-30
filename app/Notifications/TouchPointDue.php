<?php

namespace App\Notifications;

use App\Helpers\PushNotificationHelper;
use App\Models\TouchPoint;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class TouchPointDue extends Notification {
    use Queueable;

    public function __construct(protected TouchPoint $tp, protected int $days) {}

    /**
     * Only database channel; we fire FCM manually in toDatabase()
     */
    public function via($notifiable): array {
        return ['database'];
    }

    /**
     * Build & store DB record, and send push via PushNotificationHelper.
     */
    public function toDatabase($notifiable): DatabaseMessage {
        // Build the label (title) and message
        if ($this->days === 0) {
            $label = 'Due today';
        } else {
            $label = "Due in {$this->days} day" . ($this->days > 1 ? 's' : '');
        }

        $title   = $label;
        $message = "{$this->tp->name} is {$label}";

        // --- PUSH IT ---
        PushNotificationHelper::sendPushNotification(
            $notifiable->id,
            $title,
            $message
        );

        // --- SAVE IN DATABASE ---
        return new DatabaseMessage([
            'title'   => $title,
            'message' => $message,
            'type'    => 'due_soon',
            'data'    => ['touch_point_id' => $this->tp->id],
        ]);
    }
}

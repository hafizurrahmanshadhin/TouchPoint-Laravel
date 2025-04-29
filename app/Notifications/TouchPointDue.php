<?php

namespace App\Notifications;

use App\Models\TouchPoint;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class TouchPointDue extends Notification {
    use Queueable;

    public function __construct(protected TouchPoint $tp, protected int $days) {}

    public function via($notifiable): array {
        return ['database'];
    }

    public function toDatabase($notifiable): DatabaseMessage {
        // Build the “due” label:
        if ($this->days === 0) {
            $label = 'Due today';
        } else {
            $label = "Due in {$this->days} day" . ($this->days > 1 ? 's' : '');
        }

        return new DatabaseMessage([
            // Use the label as the title
            'title'   => $label,

            // And in the message, without extra quotes
            'message' => "{$this->tp->name} is {$label}",

            'type'    => 'due_soon',
            'data'    => ['touch_point_id' => $this->tp->id],
        ]);
    }
}

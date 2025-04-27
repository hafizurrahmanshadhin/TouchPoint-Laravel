<?php

namespace App\Http\Resources\Api\TouchPoint;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListTouchPointResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        $today     = Carbon::today();
        $targetDay = $this->touch_point_start_date;

        if ($targetDay->isPast() && !$targetDay->isToday()) {
            // Overdue
            $color = 'red';
            // Use $targetDay->diffInDays($today) for a positive diff
            $daysOver = $targetDay->diffInDays($today);
            $dateline = "Overdue by {$daysOver} day" . ($daysOver > 1 ? 's' : '');
        } elseif ($targetDay->isToday()) {
            $color    = 'blue';
            $dateline = 'Due today';
        } elseif ($targetDay->isTomorrow()) {
            $color    = 'yellow';
            $dateline = 'Due in 1 day';
        } else {
            // Future beyond tomorrow
            $color = 'green';
            // Use $today->diffInDays($targetDay) for a positive diff
            $daysAhead = $today->diffInDays($targetDay);
            $dateline  = "Due in {$daysAhead} day" . ($daysAhead > 1 ? 's' : '');
        }

        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'contact_method' => $this->contact_method,
            'color'          => $color,
            'dateline'       => $dateline,
        ];
    }
}

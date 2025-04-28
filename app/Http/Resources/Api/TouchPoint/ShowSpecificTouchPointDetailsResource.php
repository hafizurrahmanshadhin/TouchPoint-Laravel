<?php

namespace App\Http\Resources\Api\TouchPoint;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowSpecificTouchPointDetailsResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        $today     = Carbon::today();
        $targetDay = $this->touch_point_start_date;

        if ($targetDay->isPast() && !$targetDay->isToday()) {
            $color    = 'red';
            $daysOver = $targetDay->diffInDays($today);
            $dateline = "Overdue by {$daysOver} day" . ($daysOver > 1 ? 's' : '');
        } elseif ($targetDay->isToday()) {
            $color    = 'blue';
            $dateline = 'Due today';
        } elseif ($targetDay->isTomorrow()) {
            $color    = 'yellow';
            $dateline = 'Due in 1 day';
        } else {
            $color     = 'green';
            $daysAhead = $today->diffInDays($targetDay);
            $dateline  = "Due in {$daysAhead} day" . ($daysAhead > 1 ? 's' : '');
        }

        return [
            'id'             => $this->id,
            'avatar_url'     => $this->avatar ? asset($this->avatar) : asset('backend/images/default_images/user_1.jpg'),
            'name'           => $this->name,
            'phone_number'   => $this->phone_number,
            'contact_type'   => $this->contact_type,
            'contact_method' => $this->contact_method,
            'frequency'      => $this->frequency,
            'color'          => $color,
            'dateline'       => $dateline,
        ];
    }
}

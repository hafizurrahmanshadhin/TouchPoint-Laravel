<?php

namespace App\Http\Resources\Api\Profile;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array {
        // Determine today’s date
        $today = Carbon::today();

        // Count of completed touch points
        $completedCount = $this->touchPoints()->where('is_completed', true)->count();

        // Count of upcoming touch points (start date > today)
        $upcomingCount = $this->touchPoints()->where('touch_point_start_date', '>', $today)->count();

        return [
            'id'                    => $this->id,
            'name'                  => "{$this->first_name} {$this->last_name}",
            'email'                 => $this->email,
            'avatar_url'            => $this->avatar ? asset($this->avatar) : null,
            'badge'                 => $this->badge,
            'completed_touch_point' => (int) $completedCount,
            'upcoming_touch_point'  => (int) $upcomingCount,
        ];
    }
}

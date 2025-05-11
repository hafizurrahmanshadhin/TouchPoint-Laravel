<?php

namespace App\Http\Resources\Api\TouchPoint;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SummaryTouchPointResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        // If time field is somehow null, we default to null rather than calling format() on it.
        $formattedTime = $this->touch_point_start_time ? $this->touch_point_start_time->format('H:i') : null;

        return [
            'id'                     => $this->id,
            'avatar_url'             => $this->avatar ? asset($this->avatar) : asset('backend/images/default_images/user_1.jpg'),
            'name'                   => $this->name,
            'phone_number'           => $this->phone_number,
            'contact_type'           => $this->contact_type,
            'contact_method'         => $this->contact_method,
            'touch_point_start_date' => $this->touch_point_start_date ? $this->touch_point_start_date->toDateString() : null,
            'touch_point_start_time' => $formattedTime,
            'frequency'              => $this->frequency,
            'custom_days'            => $this->custom_days,
            'notes'                  => $this->notes,
        ];
    }
}

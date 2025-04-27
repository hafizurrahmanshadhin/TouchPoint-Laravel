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
        return [
            'id'                              => $this->id,
            'name'                            => $this->name,
            'contact_type'                    => $this->contact_type,
            'contact_method'                  => $this->contact_method,
            // Combine date and time in the desired format:
            'touch_point_start_date_and_time' => $this->touch_point_start_date
            ? $this->touch_point_start_date->format('F j, Y') . ' at ' . $this->touch_point_start_time->format('g:i A')
            : null,
            'frequency'                       => $this->frequency,
            'custom_days'                     => $this->custom_days,
            'notes'                           => $this->notes,
        ];
    }
}

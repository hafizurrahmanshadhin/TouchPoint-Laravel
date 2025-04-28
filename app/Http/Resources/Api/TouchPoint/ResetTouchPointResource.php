<?php

namespace App\Http\Resources\Api\TouchPoint;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResetTouchPointResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'badge'           => $this->resource['badge'],
            'completed_count' => $this->resource['completed_count'],
            'target_count'    => $this->resource['target_count'],
        ];
    }
}

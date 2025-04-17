<?php

namespace App\Http\Resources\Api\Contact;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Contact;
use App\Models\AddTouchpoint;
use App\Http\Resources\Api\Contact\AddTouchpointResource;

class ContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            // 'Created_at' => $this->created_at->format('Y-m-d H:i:s'),

        ];
    }

}
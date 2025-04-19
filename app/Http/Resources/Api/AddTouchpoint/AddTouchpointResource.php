<?php
namespace App\Http\Resources\Api\AddTouchpoint;

use App\Models\Contact;
use App\Models\ChoosePlan;
use App\Models\AddTouchpoint;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\Contact\ContactResource;

class AddTouchpointResource extends JsonResource
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
            'contact_id' => $this->contact_id,
            'contact_type' => $this->contact_type,
            'contact_method' => $this->contact_method,
            'start_date' => $this->start_date,
            'start_time' => $this->start_time,
            'cadence' => $this->cadence,
            'notes' => $this->notes,
            'contact' => new ContactResource($this->whenLoaded('contact')), // Optional: wrap in resource
        ];
    } 
    
}

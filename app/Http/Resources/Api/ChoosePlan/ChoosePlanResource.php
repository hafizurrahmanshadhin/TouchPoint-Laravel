<?php

namespace App\Http\Resources\Api\ChoosePlan;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\ChoosePlan;




class ChoosePlanResource extends JsonResource
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
            'plan' => $this->plan,
            'price' => $this->price,
            'billing_cycle' => $this->billing_cycle,
            'touchpoint_limit' => $this->touchpoint_limit,
            'has_ads' => $this->has_ads,
            'icon' => $this->icon,

        ];
    } 
    
}
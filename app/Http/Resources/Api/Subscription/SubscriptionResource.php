<?php
namespace App\Http\Resources\Api\Subscription;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Api\ChoosePlan\ChoosePlanResource;
use App\Http\Resources\Api\User\UserResource;
use App\Models\Subscription;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\ChoosePlan;
use App\Models\User;

class SubscriptionResource extends JsonResource
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
            'user_id'=>$this->id,
            'choose_plan_id'=>$this->id,
            // 'user' => new UserResource($this->whenLoaded('user')),
            // 'choose_plan_id' => new ChoosePlanResource($this->whenLoaded('choosePlan')),
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
        ];
    } 

}
<?php

namespace App\Models;

use App\Models\UserSubscription;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Plan extends Model {
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    protected $fillable = [
        'id',
        'subscription_plan',
        'price',
        'billing_cycle',
        'touch_points',
        'has_ads',
        'icon',
        'status',
    ];

    protected $casts = [
        'id'                => 'integer',
        'subscription_plan' => 'string',
        'price'             => 'decimal:2',
        'billing_cycle'     => 'string',
        'touch_points'      => 'integer',
        'has_ads'           => 'boolean',
        'icon'              => 'boolean',
        'status'            => 'string',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'deleted_at'        => 'datetime',
    ];

    public function getTouchPointsLabelAttribute(): string {
        return $this->touch_points === null ? 'Unlimited' : (string) $this->touch_points;
    }

    public function userSubscription(): HasMany {
        return $this->hasMany(UserSubscription::class);
    }
}

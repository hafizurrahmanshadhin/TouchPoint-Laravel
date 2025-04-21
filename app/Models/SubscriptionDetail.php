<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionDetail extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'user_id',
        'payment_subscription_id',
        'payment_customer_id',
        'subscription_plan_price_id',
        'plan_amount',
        'plan_amount_currency',
        'plan_interval',
        'plan_interval_count',
        'created',
        'plan_period_start',
        'plan_period_end',
        'trial_end',
        'status',
    ];

    protected $casts = [
        'created' => 'datetime',
        'plan_period_start' => 'datetime',
        'plan_period_end' => 'datetime',
        'trial_end' => 'datetime',
        'plan_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
}

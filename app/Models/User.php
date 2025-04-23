<?php

namespace App\Models;

use App\Models\Plan;
use App\Models\UserSubscription;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject {
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array {
        return [
            'id'                => 'integer',
            'first_name'        => 'string',
            'last_name'         => 'string',
            'email'             => 'string',
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'avatar'            => 'string',
            'cover_photo'       => 'string',
            'google_id'         => 'string',
            'apple_id'          => 'string',
            'role'              => 'string',
            'status'            => 'string',
            'remember_token'    => 'string',
            'created_at'        => 'datetime',
            'updated_at'        => 'datetime',
            'deleted_at'        => 'datetime',
        ];
    }

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array {
        return [];
    }

    public function userSubscription(): HasOne {
        return $this->hasOne(UserSubscription::class);
    }

    public function plan(): HasOneThrough {
        return $this->hasOneThrough(Plan::class, UserSubscription::class);
    }

    public function activeSubscription(): HasOne {
        return $this->hasOne(UserSubscription::class)
            ->where('status', 'active');
    }

    public function activePlan(): HasOneThrough {
        return $this->hasOneThrough(
            Plan::class,
            UserSubscription::class,
            'user_id', // FK on subscriptions
            'id', // PK on plans
            'id', // local key on users
            'plan_id' // local key on subscriptions
        )->where('user_subscriptions.status', 'active');
    }
}

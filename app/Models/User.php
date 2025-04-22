<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject {
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;


    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array {
        return [
            'id'                   => 'integer',
            'email_verified_at'    => 'datetime',
            'otp_verified_at'      => 'datetime',
            'password'             => 'hashed',
            'name'                 => 'string',
            'email'                => 'string',
            'avatar'               => 'string',
            'cover_photo'          => 'string',
            'token'                => 'string',
            'provider'             => 'string',
            'provider_id'          => 'string',
            'role'                 => 'string',
            'status'               => 'string',
            'created_at'           => 'datetime',
            'updated_at'           => 'datetime',
            'deleted_at'           => 'datetime',
        ];
    }


    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array {
        return [];
    }

    public function firebaseTokens()
    {
        return $this->hasMany(FirebaseToken::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function subscriptionDetails(): HasMany
    {
        return $this->hasMany(SubscriptionDetail::class);
    }

    public function cardDetails(): HasMany
    {
        return $this->hasMany(CardDetail::class);
    }
}

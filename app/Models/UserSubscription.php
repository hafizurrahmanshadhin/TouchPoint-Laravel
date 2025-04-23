<?php

namespace App\Models;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class UserSubscription extends Model {
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    protected $table = 'user_subscriptions';

    protected $fillable = [
        'id',
        'user_id',
        'plan_id',
        'starts_at',
        'expires_at',
        'status',
    ];

    protected $casts = [
        'id'         => 'integer',
        'user_id'    => 'integer',
        'plan_id'    => 'integer',
        'starts_at'  => 'datetime',
        'expires_at' => 'datetime',
        'status'     => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo {
        return $this->belongsTo(Plan::class);
    }
}

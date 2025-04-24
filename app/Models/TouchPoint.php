<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class TouchPoint extends Model {
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;
    protected $fillable = [
        'user_id',
        'avatar',
        'name',
        'phone_number',
        'contact_type',
        'contact_method',
        'touch_point_start_date',
        'touch_point_start_time',
        'frequency',
        'custom_days',
        'notes',
    ];

    protected $casts = [
        'id'                     => 'integer',
        'user_id'                => 'integer',
        'avatar'                 => 'string',
        'name'                   => 'string',
        'phone_number'           => 'string',
        'contact_type'           => 'string',
        'contact_method'         => 'string',
        'touch_point_start_date' => 'date',
        'touch_point_start_time' => 'datetime:H:i',
        'frequency'              => 'string',
        'custom_days'            => 'integer',
        'notes'                  => 'string',
        'status'                 => 'string',
        'created_at'             => 'datetime',
        'updated_at'             => 'datetime',
        'deleted_at'             => 'datetime',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}

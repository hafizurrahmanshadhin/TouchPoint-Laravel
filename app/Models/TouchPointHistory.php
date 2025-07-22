<?php

namespace App\Models;

use App\Models\TouchPoint;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TouchPointHistory extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'touch_point_id',
        'name',
        'contact_method',
        'completed_date',
        'original_due_date',
        'completed_at',
    ];

    protected $casts = [
        'completed_date'    => 'date',
        'original_due_date' => 'date',
        'completed_at'      => 'datetime',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function touchPoint(): BelongsTo {
        return $this->belongsTo(TouchPoint::class);
    }
}

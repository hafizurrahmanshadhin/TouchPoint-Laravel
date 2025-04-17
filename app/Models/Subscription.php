<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'choose_plan_id',
        'starts_at',
        'ends_at',
    ];
    protected $casts = [
        'user_id' => 'integer',
        'choose_plan_id' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];
    protected $dates = [
        'deleted_at',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function choosePlan()
    {
        return $this->belongsTo(ChoosePlan::class);
    }
    public function scopeActive($query)
    {
        return $query->where('ends_at', '>', now());
    }
    public function scopeExpired($query)
    {
        return $query->where('ends_at', '<=', now());
    }

    
}

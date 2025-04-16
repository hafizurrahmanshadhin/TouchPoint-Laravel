<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChoosePlan extends Model
{

    use HasFactory, SoftDeletes;
    protected $fillable = [
        'plan',
        'description',
        'price',
        'billing_cycle',
        'touchpoint_limit',
        'icon',
        'has_ads',
        'status'
    ];

    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'price' => 'string',
        'billing_cycle' => 'string',
        'touchpoint_limit' => 'string',
        'has_ads' => 'boolean',
        'icon' => 'boolean',
        'deleted_at' => 'datetime',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

}

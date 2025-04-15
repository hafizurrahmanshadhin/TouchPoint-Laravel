<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChoosePlan extends Model
{

    use HasFactory, SoftDeletes;
    protected $fillable = [
        'title',
        'description',
        'price',
        'billing_cycle',
        'touchpoint_limit',
        'has_ads',
        'status',
        
    ];

    protected $casts = [

        'id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'price' => 'string',
        'billing_cycle' => 'string',
        'touchpoint_limit' => 'integer',
        'has_ads' => 'boolean',
        'deleted_at' => 'datetime',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

}

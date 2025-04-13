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
        'button_link',
        
    ];

    protected $casts = [

        'id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'price' => 'string',
        'button_link' => 'string',
        'deleted_at' => 'datetime',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

}

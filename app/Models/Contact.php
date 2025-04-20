<?php

namespace App\Models;

use App\Models\AddTouchpoint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'phone' => 'string',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */




    /**
     * Get the user that owns the contact.
     */
    

    



}

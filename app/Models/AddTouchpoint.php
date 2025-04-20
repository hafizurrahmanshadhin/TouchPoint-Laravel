<?php

namespace App\Models;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AddTouchpoint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'number',
        'contact_type',
        'contact_method',
        'start_date',
        'start_time',
        'cadence',
        'notes',
        'contact_id',
        'status',

    ];
    protected $casts = [
        'id' => 'integer',
        'user_id'=>'integer',
        'number' =>'string',
        'contact_type' => 'string',
        'contact_method' => 'string',
        'start_date' => 'date',
        'start_time' => 'string',
        'cadence' => 'string',
        'notes' => 'string',
        'contact_id' => 'integer',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => 'string'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

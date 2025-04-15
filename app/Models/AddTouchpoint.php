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
        'contact_type',
        'contact_method',
        'start_date',
        'start_time',
        'cadence',
        'notes',
        'contact_id',
    ];
    protected $casts = [
        'id' => 'integer',
        'contact_type' => 'string',
        'contact_method' => 'string',
        'start_date' => 'date',
        'start_time' => 'time',
        'cadence' => 'string',
        'notes' => 'string',
        'contact_id' => 'integer',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}

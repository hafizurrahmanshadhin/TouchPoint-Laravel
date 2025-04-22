<?php

namespace App\Models;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class AddTouchpoint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'number',
        'contact_type',
        'contact_method',
        'start_date',
        'start_time',
        'cadence',
        'notes',
        'custom_cadence',
        'contact_id',
        'status',

    ];
    protected $casts = [
        'id' => 'integer',
        'number' => 'string',
        'contact_type' => 'string',
        'contact_method' => 'string',
        'start_date' => 'date',
        'start_time' => 'string',
        'cadence' => 'string',
        'custom_cadence' => 'string',
        'notes' => 'string',
        'contact_id' => 'integer',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status' => 'string'
    ];



    public function getDueStatusAttribute()
    {
        $date = Carbon::parse($this->start_date);
        $today = Carbon::today();

        if ($date->isToday()) {
            return 'Due today';
        } elseif ($date->isPast()) {
            return 'Overdue by ' . $date->diffInDays($today) . ' days';
        } else {
            return 'Due in ' . $today->diffInDays($date) . ' day' . ($today->diffInDays($date) > 1 ? 's' : '');
        }
    }
}

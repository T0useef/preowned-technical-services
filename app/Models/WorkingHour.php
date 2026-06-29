<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingHour extends Model
{
    protected $fillable = [
        'payment_status',
        'user_id',
        'project_id',
        'added_by_user_id',
        'date',
        'working_hours',
        'overtime_hours',
        'description',
    ];
}

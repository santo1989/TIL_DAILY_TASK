<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'report_date',
        'employee_id',
        'name',
        'designation',
        'floor',
        'in_time',
        'reason',
        'remarks'
    ];

    protected $casts = [
        'report_date' => 'date:Y-m-d',
        'in_time' => 'datetime:H:i:s'
    ];
}

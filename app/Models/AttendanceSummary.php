<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSummary extends Model
{
    use HasFactory;

    protected $table = 'attendance_summaries';

    // protected $fillable = [
    //     'report_date',
    //     'floor',
    //     'onroll',
    //     'present',
    //     'absent',
    //     'leave',
    //     'ml',
    //     'remarks'
    // ];


    protected $guarded = [];
    protected $casts = [
        'report_date' => 'date',
    ];
}

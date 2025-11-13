<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperatorAbsentAnalysis extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'report_date',
    //     'floor',
    //     'line',
    //     'employee_id',
    //     'name',
    //     'designation',
    //     'join_date',
    //     'last_p_date',
    //     'total_absent_days',
    //     'absent_reason',
    //     'come_back',
    //     'remarks'
    // ];

    protected $guarded = [];

    protected $casts = [
        'report_date' => 'date',
        'join_date' => 'date',
        'last_p_date' => 'date',
        'come_back' => 'date',
    ];
    protected $dates = [
        'report_date',
        'join_date',
        'last_p_date',
        'come_back',
    ];
    public function getReportDateAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d');
    }
}

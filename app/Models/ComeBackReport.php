<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComeBackReport extends Model
{
    use HasFactory;

    // ComeBackReport.php
    protected $fillable = [
        'report_date',
        'employee_id',
        'name',
        'designation',
        'floor',
        'absent_days',
        'reason',
        'councilor_name',
        'remarks'
    ];
}

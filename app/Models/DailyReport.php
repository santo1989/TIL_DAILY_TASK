<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_date',
        'shipments',
        'dhu_reports',
        'remarkable_incident',
        'improvement_area',
        'other_information'
    ];

    protected $casts = [
        'report_date' => 'date',
        'shipments' => 'array',
        'dhu_reports' => 'array'
    ];

}

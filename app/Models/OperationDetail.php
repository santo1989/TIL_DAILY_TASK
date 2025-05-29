<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity',
        'floor_1',
        'floor_2',
        'floor_3',
        'floor_4',
        'floor_5',
        'result',
        'remarks',
        'report_date'
    ];

    protected $casts = [
        'report_date' => 'date'
    ];
}

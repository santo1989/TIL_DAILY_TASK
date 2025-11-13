<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FloorTiming extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'floor',
    //     'starting_time',
    //     'starting_responsible',
    //     'closing_time',
    //     'closing_responsible',
    //     'remarks',
    //     'report_date'
    // ];

    protected $guarded = [];
    protected $casts = [
        'starting_time' => 'datetime:H:i',
        'closing_time' => 'datetime:H:i',
        'starting_responsible' => 'array',
        'closing_responsible' => 'array',
        'report_date' => 'date'
    ];
}

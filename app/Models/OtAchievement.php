<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtAchievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'floor',
        'two_hours_ot_persons',
        'above_two_hours_ot_persons',
        'achievement',
        'remarks',
        'report_date'
    ];

    protected $casts = [
        'report_date' => 'date'
    ];
}

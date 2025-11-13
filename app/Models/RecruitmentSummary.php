<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecruitmentSummary extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'interview_date',
    //     'Candidate',
    //     'selected',
    //     'designation',
    //     'time_of_entrance',
    //     'test_taken_time',
    //     'test_taken_floor',
    //     'test_taken_by',
    //     'grade',
    //     'salary',
    //     'probable_date_of_joining',
    //     'allocated_floor',
    //     'remarks'
    // ];

    protected $guarded = [];
    protected $casts = [
        'interview_date' => 'date',
        'probable_date_of_joining' => 'date',
        'time_of_entrance' => 'datetime:H:i:s',
        'test_taken_time' => 'datetime:H:i:s',
    ];
}

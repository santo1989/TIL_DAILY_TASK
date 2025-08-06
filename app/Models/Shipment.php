<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipment_date',
        'export_qty',
        'export_value',
        'remarks',
        'report_date'
    ];

    protected $casts = [
        'shipment_date' => 'date',
        'report_date' => 'date'
    ];
}

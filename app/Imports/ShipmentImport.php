<?php

namespace App\Imports;

use App\Models\Shipment;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ShipmentImport implements ToCollection, WithHeadingRow
{
    protected $reportDate;

    public function __construct($reportDate)
    {
        $this->reportDate = Carbon::parse($reportDate);
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Shipment::updateOrCreate(
                [
                    'floor' => $row['floor'],
                    'report_date' => $this->reportDate
                ],
                [
                    'two_hours_ot_persons' => $this->cleanNumber($row['2_hours_ot_persons']),
                    'above_two_hours_ot_persons' => $this->cleanNumber($row['above_2_hours_ot_persons']),
                    'achievement' => $this->parseAchievement($row['achievement']),
                    'remarks' => $row['remarks'] ?? null
                ]
            );
        }
    }

    private function cleanNumber($value)
    {
        return is_numeric($value) ? (int)$value : 0;
    }

    private function parseAchievement($value)
    {
        // Handle formulas like "=8665+10099"
        if (str_starts_with($value, '=')) {
            try {
                return eval('return ' . substr($value, 1) . ';');
            } catch (\Throwable $e) {
                return 0;
            }
        }
        return (float)$value;
    }
}

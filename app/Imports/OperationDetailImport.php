<?php

namespace App\Imports;

use App\Models\OperationDetail;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OperationDetailImport implements ToCollection, WithHeadingRow
{
    protected $reportDate;

    public function __construct($reportDate)
    {
        $this->reportDate = Carbon::parse($reportDate);
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            OperationDetail::updateOrCreate(
                [
                    'activity' => $row['activities'],
                    'report_date' => $this->reportDate
                ],
                [
                    'floor_1' => $this->cleanNumber($row['1st_floor']),
                    'floor_2' => $this->cleanNumber($row['2nd_floor']),
                    'floor_3' => $this->cleanNumber($row['3rd_floor']),
                    'floor_4' => $this->cleanNumber($row['4th_floor']),
                    'floor_5' => $this->cleanNumber($row['5th_floor']),
                    'result' => $this->cleanNumber($row['total_average']), // Correct column name
                    'remarks' => $row['remarks'] ?? null
                ]
            );
        }
    }

    private function cleanNumber($value)
    {
        // Remove non-numeric characters except digits, decimal, and minus
        $cleaned = preg_replace('/[^0-9\.\-]/', '', $value);
        return is_numeric($cleaned) ? (float)$cleaned : null;
    }
}

<?php

namespace App\Imports;

use App\Models\AttendanceSummary;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AttendanceSummaryImport implements ToCollection, WithHeadingRow, WithValidation
{
    protected $reportDate;

    public function __construct($reportDate = null)
    {
        $this->reportDate = $reportDate ?: Carbon::today()->format('Y-m-d');
    }


    public function collection(Collection $rows)
    {
        // dd($rows);

        foreach ($rows as $row) {
            if (strtolower($row['floor']) === 'total') continue;

            AttendanceSummary::updateOrCreate(
                [
                    'report_date' => $this->reportDate,
                    'floor' => $row['floor']
                ],
                [
                    'onroll' => $row['onroll'],
                    'present' => $row['present'],
                    'absent' => $row['absent'],
                    'leave' => $row['leave'],
                    'ml' => $row['ml'],
                    'remarks' => $row['remarks'] ?? null,
                ]
            );
        }
    }

    public function rules(): array
    {
        return [
            'floor' => 'required|string',
            'onroll' => 'required|integer|min:0',
            'present' => 'required|integer|min:0',
            'absent' => 'required|integer|min:0',
            'leave' => 'required|integer|min:0',
            'ml' => 'required|integer|min:0',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'ml.required' => 'The ML field is required',
            'ml.integer' => 'The ML must be a number',
        ];
    }
}

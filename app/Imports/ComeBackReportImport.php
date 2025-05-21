<?php

namespace App\Imports;

use App\Models\ComeBackReport;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ComeBackReportImport implements ToCollection, WithHeadingRow, WithValidation
{
    protected $reportDate;

    public function __construct($reportDate = null)
    {
        $this->reportDate = $reportDate ?: now()->format('Y-m-d');
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Extract numeric value from 'No of absent days'
            $absentDays = (int) preg_replace('/[^0-9]/', '', $row['no_of_absent_days']);

            //all fields data save in UpperCase
            $row['name'] = strtoupper($row['name']);
            $row['designation'] = strtoupper($row['designation']);
            // in floor only number save
            $row['floor'] = preg_replace('/[^0-9]/', '', $row['floor']);
            $row['reason_for_absent'] = strtoupper($row['reason_for_absent']);
            $row['councilor_name'] = strtoupper($row['councilor_name']);
            $row['remarks'] = strtoupper($row['remarks'] ?? '');
            $row['id'] = strtoupper($row['id']);

            ComeBackReport::updateOrCreate(
                [
                    'report_date' => $this->reportDate,
                    'employee_id' => (string) $row['id']
                ],
                [
                    'name' => $row['name'],
                    'designation' => $row['designation'],
                    'floor' => $row['floor'],
                    'absent_days' => $absentDays,
                    'reason' => $row['reason_for_absent'],
                    'councilor_name' => $row['councilor_name'],
                    'remarks' => $row['remarks'] ?? null,
                ]
            );
        }
    }

    public function rules(): array
    {
        return [
            'id' => 'required',
            'name' => 'required|string',
            'designation' => 'required|string',
            'floor' => 'required|string',
            'no_of_absent_days' => 'required|string',
            'reason_for_absent' => 'required|string',
            'councilor_name' => 'required|string'
        ];
    }
}

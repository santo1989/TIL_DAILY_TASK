<?php

namespace App\Imports;

use App\Models\OperatorAbsentAnalysis;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class OperatorAbsentAnalysisImport implements ToCollection, WithStartRow, WithValidation
{
    protected $reportDate;

    public function __construct($reportDate = null)
    {
        $this->reportDate = $reportDate ?: now()->format('Y-m-d');
    }

    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows)
    {
        // Clean up any previous header imports
        OperatorAbsentAnalysis::where('employee_id', 'ID Card NO.')->delete();

        foreach ($rows as $row) {
            if (empty($row[2])) {
                continue;
            }

            $employeeId = $this->toString($row[2]);

            OperatorAbsentAnalysis::updateOrCreate(
                [
                    'report_date' => $this->reportDate,
                    'employee_id' => $employeeId
                ],
                [
                    'floor'         => $row[0] ?? null,
                    'name'          => $row[3] ?? null,
                    'designation'   => $row[4] ?? null,
                    'join_date'     => $this->parseDate($row[5] ?? null),
                    'line'          => $this->parseInt($row[6] ?? null),
                    'total_absent_days' => $this->parseInt($row[7] ?? null),
                    'last_p_date'   => $this->parseDate($row[8] ?? null),
                    'absent_reason' => $this->normalizeReason($row[9] ?? null),
                    'come_back'     => $this->parseDate($row[10] ?? null),
                    'remarks'       => $row[11] ?? null,
                ]
            );
        }
    }

    private function toString($value)
    {
        if (is_numeric($value)) {
            return sprintf('%.0f', $value);
        }
        return (string) $value;
    }

    private function parseInt($value)
    {
        return is_numeric($value) ? (int)$value : null;
    }

    private function parseDate($value)
    {
        if (empty($value)) {
            return null;
        }

        $value = trim($value);

        // Handle Excel serial dates
        if (is_numeric($value)) {
            try {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
            } catch (\Exception $e) {
                return null;
            }
        }

        // Handle Y-m-d format (e.g., 2021-08-14)
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return Carbon::createFromFormat('Y-m-d', $value);
        }

        // Handle d.m.y format (e.g., 28.07.25)
        if (preg_match('/^\d{1,2}\.\d{1,2}\.\d{2,4}$/', $value)) {
            // Split date components
            $parts = explode('.', $value);
            $day = (int)$parts[0];
            $month = (int)$parts[1];
            $year = (int)$parts[2];

            // Handle 2-digit years
            if ($year < 100) {
                $year += $year < 50 ? 2000 : 1900;
            }

            return Carbon::create($year, $month, $day);
        }

        // Handle other formats using loose parsing
        try {
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function normalizeReason($reason)
    {
        $map = [
            'phne not received' => 'Phone Not Received',
            'phone not received' => 'Phone Not Received',
            'mobil off' => 'Mobile Off',
            'family problem' => 'Family Problem',
            'sick' => 'Sick',
            'resign' => 'Resign',
            'leave' => 'Leave',
            'phone off' => 'Phone Off',
        ];

        $reason = strtolower(trim($reason));
        return $map[$reason] ?? ucwords($reason);
    }

    public function rules(): array
    {
        return [
            '2' => 'required',
            '3' => 'required',
            '4' => 'required',
            '5' => 'required',
            '7' => 'required',
            '9' => 'required',
        ];
    }
}
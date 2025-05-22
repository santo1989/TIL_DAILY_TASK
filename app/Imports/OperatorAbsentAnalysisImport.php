<?php

namespace App\Imports;

use App\Models\OperatorAbsentAnalysis;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class OperatorAbsentAnalysisImport implements ToCollection, WithHeadingRow, WithValidation
{
    protected $reportDate;

    public function __construct($reportDate = null)
    {
        $this->reportDate = $reportDate ?: now()->format('Y-m-d');
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Handle different column naming variations
            $employeeId = $this->getColumnValue($row, ['id_card_no', 'id_card_no_']);
            $absentDays = $this->parseAbsentDays($row);
            $lastPDate = $this->parseDate($row['last_p_date'] ?? null);
            $comeBack = $this->parseDate($row['come_back'] ?? null);

            OperatorAbsentAnalysis::updateOrCreate(
                [
                    'report_date' => $this->reportDate,
                    'employee_id' => $employeeId
                ],
                [
                    'floor' => $row['floor'],
                    'name' => $row['name'],
                    'designation' => $row['designation'],
                    'join_date' => $this->parseDate($row['join_date']),
                    'line' => (int) $row['line'],
                    'total_absent_days' => $absentDays,
                    'last_p_date' => $lastPDate,
                    'absent_reason' => $this->normalizeReason($row['absent_reason']),
                    'come_back' => $comeBack,
                    'remarks' => $this->getColumnValue($row, ['remark', 'remarks'])
                ]
            );
        }
    }

    private function getColumnValue($row, $possibleKeys)
    {
        foreach ($possibleKeys as $key) {
            if (isset($row[$key])) {
                return $row[$key];
            }
        }
        return null;
    }

    private function parseAbsentDays($row)
    {
        $value = $this->getColumnValue($row, ['total_absent_day', 'total_absent_days']);
        return (int) preg_replace('/[^0-9]/', '', $value);
    }

    private function parseDate($value)
    {
        try {
            if (is_numeric($value)) {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
            }
            return Carbon::createFromFormat('d.m.y', $value);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function normalizeReason($reason)
    {
        $map = [
            'mobil off' => 'Mobile Off',
            'ph,not received' => 'Phone Not Received',
            'family problem' => 'Family Problem'
        ];
        return $map[strtolower($reason)] ?? ucfirst($reason);
    }

    public function rules(): array
    {
        return [
            'id_card_no' => 'required',
            'name' => 'required',
            'designation' => 'required',
            'join_date' => 'required',
            'line' => 'required|numeric',
            'total_absent_day' => 'required',
            'absent_reason' => 'required'
        ];
    }
}

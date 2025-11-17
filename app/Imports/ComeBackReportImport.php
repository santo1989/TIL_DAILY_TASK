<?php

namespace App\Imports;

use App\Models\ComeBackReport;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;

class ComeBackReportImport implements ToCollection
{
    protected $reportDate;

    public function __construct($reportDate = null)
    {
        $this->reportDate = $reportDate ?: now()->format('Y-m-d');
    }

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            return;
        }

        // Try to detect header row within the first 3 rows
        $expected = [
            'id',
            'name',
            'designation',
            'floor',
            'no_of_absent_days',
            'reason_for_absent',
            'councilor_name',
            'remarks'
        ];

        $headerRowIndex = null;
        $maxCheck = min(3, $rows->count());

        for ($i = 0; $i < $maxCheck; $i++) {
            $cells = array_values($rows[$i]->toArray());
            $slugs = array_map(function ($v) {
                return Str::slug((string) $v, '_');
            }, $cells);

            $matches = array_intersect($slugs, $expected);
            if (count($matches) >= 3) { // found likely header row
                $headerRowIndex = $i;
                $headerSlugs = $slugs;
                break;
            }
        }

        // Default: assume first row is header
        if ($headerRowIndex === null) {
            $headerRowIndex = 0;
            $headerSlugs = array_map(function ($v) {
                return Str::slug((string) $v, '_');
            }, array_values($rows[0]->toArray()));
        }

        // Build header map: index -> slug
        $headerMap = $headerSlugs;

        // Process each data row after headerRowIndex
        for ($r = $headerRowIndex + 1; $r < $rows->count(); $r++) {
            $cells = array_values($rows[$r]->toArray());

            // Map cells to keys using headerMap
            $assoc = [];
            foreach ($headerMap as $idx => $key) {
                $assoc[$key] = isset($cells[$idx]) ? $cells[$idx] : null;
            }

            // Normalize fields
            $data = [
                'id' => strtoupper(trim((string) ($assoc['id'] ?? ''))),
                'name' => strtoupper(trim((string) ($assoc['name'] ?? ''))),
                'designation' => strtoupper(trim((string) ($assoc['designation'] ?? ''))),
                'floor' => preg_replace('/[^0-9]/', '', (string) ($assoc['floor'] ?? '')),
                'no_of_absent_days' => (string) ($assoc['no_of_absent_days'] ?? $assoc['no_of_absent'] ?? ''),
                'reason_for_absent' => strtoupper(trim((string) ($assoc['reason_for_absent'] ?? $assoc['reason_for_absence'] ?? $assoc['reason'] ?? ''))),
                'councilor_name' => strtoupper(trim((string) ($assoc['councilor_name'] ?? $assoc['councilor'] ?? ''))),
                'remarks' => strtoupper(trim((string) ($assoc['remarks'] ?? ''))),
            ];

            // Validate the row
            $validator = Validator::make($data, [
                'id' => 'required',
                'name' => 'required|string',
                'designation' => 'required|string',
                'floor' => 'required|string',
                'no_of_absent_days' => 'required|string',
                'reason_for_absent' => 'required|string',
                'councilor_name' => 'required|string'
            ]);

            if ($validator->fails()) {
                // Throw a ValidationException with messages so controller can show details
                throw ValidationException::withMessages($validator->errors()->toArray());
            }

            $absentDays = (int) preg_replace('/[^0-9]/', '', $data['no_of_absent_days']);

            // Save/update
            ComeBackReport::updateOrCreate(
                [
                    'report_date' => $this->reportDate,
                    'employee_id' => (string) $data['id']
                ],
                [
                    'name' => $data['name'],
                    'designation' => $data['designation'],
                    'floor' => $data['floor'],
                    'absent_days' => $absentDays,
                    'reason' => $data['reason_for_absent'],
                    'councilor_name' => $data['councilor_name'],
                    'remarks' => $data['remarks'] ?: null,
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

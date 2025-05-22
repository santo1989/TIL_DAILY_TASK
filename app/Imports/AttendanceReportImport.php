<?php

namespace App\Imports;

use App\Models\AttendanceReport;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AttendanceReportImport implements ToCollection, WithHeadingRow, WithValidation
{
protected $reportDate;

public function __construct($reportDate = null)
{
$this->reportDate = $reportDate ?: now()->format('Y-m-d');
}

    // public function collection(Collection $rows)
    // {
    // foreach ($rows as $row) {
    // AttendanceReport::create([
    // 'type' => $this->determineType($row),
    // 'report_date' => $this->reportDate,
    // 'employee_id' => $row['id'],
    // 'name' => $row['name'],
    // 'designation' => $row['designation'],
    // 'floor' => $row['floor'] ?? null,
    // 'in_time' => isset($row['in_time']) ? $this->parseTime($row['in_time']) : null,
    // 'reason' => $row['reason_of_leave'] ?? $row['reason_of_lunch_out'] ?? null,
    // 'remarks' => $row['remarks'] ?? null
    // ]);
    // }
    // }

    // private function determineType($row)
    // {
    // if (isset($row['in_time'])) return 'late_comer';
    // if (isset($row['reason_of_lunch_out'])) return 'lunch_out';
    // if (isset($row['reason_of_leave'])) return $row['type'] == 'On Leave' ? 'on_leave' : 'to_be_absent';
    // return 'other';
    // }

    // private function parseTime($value)
    // {
    // try {
    // return Carbon::instance(Date::excelToDateTimeObject($value));
    // } catch (\Exception $e) {
    // return Carbon::createFromFormat('H:i:s', $value);
    // }
    // }

    public function rules(): array
    {
    return [
    'id' => 'required',
    'name' => 'required',
    'designation' => 'required',
    'type' => 'required|in:Lunch Out,Late Comer,To be Absent,On Leave'
    ];
    }

    // private function parseTime($value)
    // {
    //     try {
    //         // Handle Excel timestamp values
    //         if (is_numeric($value)) {
    //             return Carbon::instance(Date::excelToDateTimeObject($value));
    //         }

    //         // Clean and normalize time strings
    //         $cleaned = preg_replace('/[^0-9:]/', '', $value);

    //         // Try different time formats
    //         foreach (['H:i:s', 'H:i', 'g:i A', 'g:i:s A'] as $format) {
    //             try {
    //                 return Carbon::createFromFormat($format, $cleaned);
    //             } catch (\Exception $e) {
    //                 continue;
    //             }
    //         }

    //         // Fallback to current time if parsing fails
    //         return now();
    //     } catch (\Exception $e) {
    //         // Log error and return null
    //         // \log::error("Time parsing failed for value: {$value}", ['error' => $e]);
    //         // return null;
    //     }
    // }


    private function parseTime($value)
    {
        try {
            // Handle Excel timestamp values
            if (is_numeric($value)) {
                return Carbon::instance(Date::excelToDateTimeObject($value));
            }

            // Clean and normalize time strings
            $cleaned = preg_replace('/[^0-9:]/', '', $value);

            // Try different time formats
            foreach (['H:i:s', 'H:i', 'g:i A', 'g:i:s A'] as $format) {
                try {
                    return Carbon::createFromFormat($format, $cleaned);
                } catch (\Exception $e) {
                    continue;
                }
            }

            // Fallback to null if parsing fails
            return null;
        } catch (\Exception $e) {
            // \Log::error("Time parsing failed for value: {$value}", ['error' => $e]);
            return null;
        }
    }
    private function determineType($row)
    {
        $type = strtolower($row['type'] ?? '');

        $typeMap = [
            'to be absent' => 'to_be_absent',
            'late comer' => 'late_comer',
            'lunch out' => 'lunch_out',
            'on leave' => 'on_leave'
        ];

        return $typeMap[$type] ?? 'other';
    }

    // public function collection(Collection $rows)
    // {
    //     foreach ($rows as $row) {
    //         try {
    //             AttendanceReport::create([
    //                 'type' => $this->determineType($row),
    //                 'report_date' => Carbon::parse($this->reportDate),
    //                 'employee_id' => $this->cleanValue($row['id']),
    //                 'name' => $this->cleanValue($row['name']),
    //                 'designation' => $this->cleanValue($row['designation']),
    //                 'floor' => $this->cleanValue($row['floor'] ?? null),
    //                 'in_time' => isset($row['in_time']) ? $this->parseTime($row['in_time']) : null,
    //                 'reason' => $this->cleanValue(
    //                     $row['reason_of_leave'] ??
    //                         $row['reason_of_lunch_out'] ??
    //                         null
    //                 ),
    //                 'remarks' => $this->cleanValue($row['remarks'] ?? null)
    //             ]);
    //         } catch (\Exception $e) {
    //             // \log::error("Error importing row: " . json_encode($row), ['error' => $e]);
    //             // continue;
    //         }
    //     }
    // }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                $inTime = null;
                if (isset($row['in_time'])) {
                    $parsedTime = $this->parseTime($row['in_time']);
                    $inTime = $parsedTime ? $parsedTime->format('H:i:s') : null;
                }

                AttendanceReport::create([
                    'type' => $this->determineType($row),
                    'report_date' => Carbon::parse($this->reportDate),
                    'employee_id' => $this->cleanValue($row['id']),
                    'name' => $this->cleanValue($row['name']),
                    'designation' => $this->cleanValue($row['designation']),
                    'floor' => $this->cleanValue($row['floor'] ?? null),
                    'in_time' => $inTime, // Use formatted time string
                    'reason' => $this->cleanValue(
                        $row['reason_of_leave'] ??
                            $row['reason_of_lunch_out'] ??
                            null
                    ),
                    'remarks' => $this->cleanValue($row['remarks'] ?? null)
                ]);
            } catch (\Exception $e) {
                // \Log::error("Error importing row: " . json_encode($row), ['error' => $e]);
                continue;
            }
        }
    }
    
    private function cleanValue($value)
    {
        return is_string($value) ? trim(preg_replace('/\s+/', ' ', $value)) : $value;
    }
}
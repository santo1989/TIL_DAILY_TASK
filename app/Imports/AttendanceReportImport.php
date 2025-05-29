<?php

namespace App\Imports;

use App\Models\AttendanceReport;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Date;
use PhpOffice\PhpSpreadsheet\Shared\Date;
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
    //     if (empty(trim($value))) {
    //         return null;
    //     }

    //     try {
    //         // Handle Excel numeric timestamp (e.g., 0.5 = 12:00:00)
    //         if (is_numeric($value)) {
    //             $dateTime = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
    //             return Carbon::instance($dateTime)->format('H:i:s'); // Format to 24-hour time
    //         }

    //         // Clean and parse string time values
    //         $value = trim(str_replace(["\xc2\xa0", "\u{200E}", "\u{200F}"], ' ', $value));
    //         $value = preg_replace('/\s+/', ' ', $value);

    //         // Parse time formats (e.g., "09:20:00" or "9:20 AM")
    //         $formats = ['H:i:s', 'H:i', 'g:i A', 'g:i:s A'];
    //         foreach ($formats as $format) {
    //             try {
    //                 $time = Carbon::createFromFormat($format, $value);
    //                 return $time->format('H:i:s'); // Force 24-hour format
    //             } catch (\Exception $e) {
    //                 continue;
    //             }
    //         }

    //         // Fallback for formats like "9.20 AM"
    //         if (preg_match('/(\d+)[.:](\d+)(?:[.:](\d+))?\s*(AM|PM)?/i', $value, $matches)) {
    //             $hour = (int)$matches[1];
    //             $minute = (int)$matches[2];
    //             $second = $matches[3] ?? 0;
    //             $period = strtoupper($matches[4] ?? '');

    //             if ($period === 'PM' && $hour < 12) $hour += 12;
    //             if ($period === 'AM' && $hour == 12) $hour = 0;

    //             return Carbon::createFromTime($hour, $minute, $second)->format('H:i:s');
    //         }

    //         return null;
    //     } catch (\Exception $e) {
    //         \Log::error("Time parsing failed: " . $e->getMessage());
    //         return null;
    //     }
    // }






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

    //         // Fallback to null if parsing fails
    //         return null;
    //     } catch (\Exception $e) {
    //         // \Log::error("Time parsing failed for value: {$value}", ['error' => $e]);
    //         return null;
    //     }
    // }


    private function parseTime($value)
    {
        if (empty(trim($value))) {
            return null;
        }

        try {
            // Handle Excel numeric timestamp (e.g., 0.51388888888889 = 12:20:00 PM)
            if (is_numeric($value)) {
                $dateTime = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                return Carbon::instance($dateTime)->format('h:i:s A'); // 12-hour format with AM/PM
            }

            // Clean and parse string time values
            $value = trim(str_replace(["\xc2\xa0", "\u{200E}", "\u{200F}"], ' ', $value));
            $value = preg_replace('/\s+/', ' ', $value);

            // Parse time formats (e.g., "09:20:00 AM" or "9:20 PM")
            $formats = ['h:i:s A', 'h:i A', 'g:i A', 'g:i:s A'];
            foreach ($formats as $format) {
                try {
                    $time = Carbon::createFromFormat($format, $value);
                    return $time->format('h:i:s A'); // Force 12-hour format
                } catch (\Exception $e) {
                    continue;
                }
            }

            // Fallback for formats like "12.20 PM"
            if (preg_match('/(\d+)[.:](\d+)(?:[.:](\d+))?\s*(AM|PM)?/i', $value, $matches)) {
                $hour = (int)$matches[1];
                $minute = (int)$matches[2];
                $second = $matches[3] ?? 0;
                $period = strtoupper($matches[4] ?? '');

                if ($period === 'PM' && $hour < 12) $hour += 12;
                if ($period === 'AM' && $hour == 12) $hour = 0;

                return Carbon::createFromTime($hour, $minute, $second)->format('h:i:s A');
            }

            return null;
        } catch (\Exception $e) {
            \Log::error("Time parsing failed: " . $e->getMessage());
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

    private function cleanValue($value)
    {
        return is_string($value) ? trim(preg_replace('/\s+/', ' ', $value)) : $value;
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
                $inTime = isset($row['in_time']) && !empty(trim($row['in_time']))
                    ? $this->parseTime($row['in_time'])
                    : null;


                $existingReport = AttendanceReport::where('report_date', Carbon::parse($this->reportDate))
                    ->where('employee_id', $this->cleanValue($row['id']))
                    ->first();

                $data = [
                    'type' => $this->determineType($row),
                    'report_date' => Carbon::parse($this->reportDate),
                    'employee_id' => $this->cleanValue($row['id']),
                    'name' => $this->cleanValue($row['name']),
                    'designation' => $this->cleanValue($row['designation']),
                    'floor' => $this->cleanValue($row['floor'] ?? null),
                    'in_time' => $inTime, // Already formatted as "H:i:s"
                    'reason' => $this->cleanValue(
                        $row['reason_of_leave'] ?? $row['reason_of_lunch_out'] ?? null
                    ),
                    'remarks' => $this->cleanValue($row['remarks'] ?? null)
                ];

                if ($existingReport) {
                    $existingReport->update($data);
                } else {
                    AttendanceReport::create($data);
                }
            } catch (\Exception $e) {
                \Log::error("Error processing row: " . $e->getMessage());
                continue;
            }
        }
    }
}

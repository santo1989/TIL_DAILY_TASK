<?php

namespace App\Imports;

use App\Models\RecruitmentSummary;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class RecruitmentSummaryImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        $counter = -1;
        foreach ($rows as $row) {
            $counter++;
            Log::info("Processing row: " . $counter);
            Log::info(print_r($row->toArray(), true)); // Log the entire row data

            try {
                $interviewDate = now()->format('Y-m-d');
                $Candidate = $row['candidate'];
                // dd($Candidate);

                Log::info("candidate: " . $Candidate . ", Interview Date: " . $interviewDate);

                //check if Candidate already exists
                $existingSummary = RecruitmentSummary::where('Candidate', $Candidate)
                    ->where('interview_date', $interviewDate)->where('designation', $this->cleanValue($row['designation']))
                    ->where('test_taken_by', $this->cleanValue($row['test_taken_by_ie_dept']))
                    ->where('test_taken_time', $this->parseTime($row['test_teken_time']))
                    ->where('test_taken_floor', $this->cleanValue($row['test_taken_floor']))
                    ->where('grade', $this->cleanValue($row['grade']))
                    ->where('salary', $this->parseSalary($row['salary']))
                    ->where('probable_date_of_joining', $this->parseDate($row['probable_date_of_joining']))
                    ->where('allocated_floor', $this->cleanValue($row['allocated_floor']))
                    ->first();
                if ($existingSummary) {
                    Log::info("Candidate already exists for this interview date. Skipping row.");
                    continue; // Skip this row if Candidate already exists
                }

                RecruitmentSummary::updateOrCreate(
                    [
                        'Candidate' => $Candidate,
                        'interview_date' => $interviewDate,
                        'selected' => $this->cleanValue($row['selected_yes_no'] ?? null)
                    ],
                    [
                        'designation' => $this->cleanValue($row['designation']),
                        'time_of_entrance' => $this->parseTime($row['time_of_entrance']),
                        'test_taken_time' => $row['test_teken_time'],
                        'test_taken_floor' => $this->cleanValue($row['test_taken_floor']),
                        'test_taken_by' => $this->cleanValue($row['test_taken_by_ie_dept']),
                        'grade' => $this->cleanValue($row['grade']),
                        'salary' => $this->parseSalary($row['salary']),
                        'probable_date_of_joining' => $this->parseDate($row['probable_date_of_joining']),
                        'allocated_floor' => $this->cleanValue($row['allocated_floor']),
                        'remarks' => $this->cleanValue($row['remarks']),
                    ]
                );

                Log::info("Row processed successfully.");
            } catch (\Exception $e) {
                Log::error("Recruitment import error: " . $e->getMessage());
                continue;
            }
        }
        Log::info("Total rows processed: " . $counter);
    }

    public function rules(): array
    {
        return [
            'candidate' => 'required|string',
            'selected_yes_no' => 'required|string',
            'designation' => 'required|string',
            'test_teken_time' => 'nullable|string',
            'test_taken_floor' => 'nullable|string',
            'salary' => 'nullable',
        ];
    }

    private function parseSalary($value)
    {
        $value = $this->cleanValue($value);
        return ($value === 'N/A' || $value === '') ? null : $value;
    }

    private function parseTime($value)
    {
        $value = $this->cleanValue($value);
        if ($value === 'N/A' || empty($value)) {
            return null;
        }

        try {
            if (is_numeric($value)) {
                $dateTime = Date::excelToDateTimeObject($value);
                return Carbon::instance($dateTime)->format('H:i:s');
            }

            $value = trim(str_replace(["\xc2\xa0", "\u{200E}", "\u{200F}"], ' ', $value));
            $value = preg_replace('/\s+/', ' ', $value);

            $formats = ['H:i:s', 'H:i', 'g:i A', 'g:i:s A'];
            foreach ($formats as $format) {
                try {
                    return Carbon::createFromFormat($format, $value)->format('H:i:s');
                } catch (\Exception $e) {
                    continue;
                }
            }

            if (preg_match('/(\d+)[.:](\d+)(?:[.:](\d+))?\s*(AM|PM)?/i', $value, $matches)) {
                $hour = (int)$matches[1];
                $minute = (int)$matches[2];
                $second = $matches[3] ?? 0;
                $period = strtoupper($matches[4] ?? '');

                if ($period === 'PM' && $hour < 12) $hour += 12;
                if ($period === 'AM' && $hour == 12) $hour = 0;

                return Carbon::createFromTime($hour, $minute, $second)->format('H:i:s');
            }

            return null;
        } catch (\Exception $e) {
            Log::error("Time parsing failed: " . $e->getMessage());
            return null;
        }
    }

    private function parseDate($value)
    {
        $value = $this->cleanValue($value);
        if ($value === 'N/A' || empty($value)) {
            return null;
        }

        try {
            if (is_numeric($value)) {
                return Carbon::instance(Date::excelToDateTimeObject($value))->format('Y-m-d');
            }
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            Log::error("Date parsing failed: " . $e->getMessage());
            return null;
        }
    }

    private function cleanValue($value)
    {
        $cleaned = is_string($value) ? trim(preg_replace('/[[:cntrl:]]/', '', preg_replace('/\s+/', ' ', $value))) : $value;
        return ($cleaned === 'N/A' || empty($cleaned)) ? "" : $cleaned;
    }
}

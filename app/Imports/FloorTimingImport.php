<?php

// app/Imports/FloorTimingImport.php

namespace App\Imports;

use App\Models\FloorTiming;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FloorTimingImport implements ToCollection, WithHeadingRow
{
    protected $reportDate;

    public function __construct($reportDate)
    {
        $this->reportDate = Carbon::parse($reportDate);
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            FloorTiming::updateOrCreate(
                [
                    'floor' => $row['floor'],
                    'report_date' => $this->reportDate
                ],
                [
                    'starting_time' => $this->parseTime($row['starting_time']),
                    'starting_responsible' => $this->parseResponsible($row['responsible_person']),
                    'closing_time' => $this->parseTime($row['clossing_time']),
                    'closing_responsible' => $this->parseResponsible($row['responsible_person_1']),
                    'remarks' => $row['remarks'] ?? null
                ]
            );
        }
    }

    private function parseTime($value)
    {
        try {
            return Carbon::createFromFormat('H:i', $value);
        } catch (\Exception $e) {
            return now()->setTime(0, 0);
        }
    }

    private function parseResponsible($value)
    {
        return collect(explode(', ', $value))
            ->map(function ($item) {
                preg_match('/(.*?)\s*\((.*?)\)/', $item, $matches);
                return [
                    'name' => $matches[1] ?? $item,
                    'role' => $matches[2] ?? 'Unknown'
                ];
            });
    }
}
<?php

// namespace App\Imports;

// use App\Models\OperationDetail;
// use Carbon\Carbon;
// use Illuminate\Support\Collection;
// use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\WithHeadingRow;

// class OperationDetailImport implements ToCollection, WithHeadingRow
// {
//     protected $reportDate;

//     public function __construct($reportDate)
//     {
//         $this->reportDate = Carbon::parse($reportDate);
//     }

//     public function collection(Collection $rows)
//     {
//         foreach ($rows as $row) {
//             OperationDetail::updateOrCreate(
//                 [
//                     'activity' => $row['activities'],
//                     'report_date' => $this->reportDate
//                 ],
//                 [
//                     'floor_1' => $this->cleanNumber($row['1st_floor']),
//                     'floor_2' => $this->cleanNumber($row['2nd_floor']),
//                     'floor_3' => $this->cleanNumber($row['3rd_floor']),
//                     'floor_4' => $this->cleanNumber($row['4th_floor']),
//                     'floor_5' => $this->cleanNumber($row['5th_floor']),
//                     'result' => $this->cleanNumber($row['total_average']), // Correct column name
//                     'remarks' => $row['remarks'] ?? null
//                 ]
//             );
//         }
//     }

//     private function cleanNumber($value)
//     {
//         // Remove non-numeric characters except digits, decimal, and minus
//         $cleaned = preg_replace('/[^0-9\.\-]/', '', $value);
//         return is_numeric($cleaned) ? (float)$cleaned : null;
//     }
// }

// app/Imports/OperationDetailImport.php

namespace App\Imports;

use App\Models\OperationDetail;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OperationDetailImport implements ToCollection, WithHeadingRow
{
    protected $reportDate;
    private $averageActivities = [
        'MMR',
        'Present Operator/ Manpower',
        'Efficiency',
        'Produce Minutes',
        'No of Garments Sewing Completed',
        'No of Garments Packed Completed',
        'DHU%'
    ];

    public function __construct($reportDate)
    {
        $this->reportDate = Carbon::parse($reportDate);
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $activity = trim($row['activities']);
            $values = [
                $this->cleanNumber($row['1st_floor']),
                $this->cleanNumber($row['2nd_floor']),
                $this->cleanNumber($row['3rd_floor']),
                $this->cleanNumber($row['4th_floor']),
                $this->cleanNumber($row['5th_floor'])
            ];

            // Calculate result based on activity type
            $result = in_array($activity, $this->averageActivities)
                ? $this->calculateAverage($values)
                : $this->calculateSum($values);

            OperationDetail::updateOrCreate(
                [
                    'activity' => $activity,
                    'report_date' => $this->reportDate
                ],
                [
                    'floor_1' => $values[0],
                    'floor_2' => $values[1],
                    'floor_3' => $values[2],
                    'floor_4' => $values[3],
                    'floor_5' => $values[4],
                    'result' => $result,
                    'remarks' => $row['remarks'] ?? null
                ]
            );
        }
    }

    private function cleanNumber($value)
    {
        if (is_numeric($value)) return (float)$value;
        if (empty($value)) return null;
        
        $cleaned = preg_replace('/[^0-9\.\-]/', '', $value);
        return is_numeric($cleaned) ? (float)$cleaned : null;
    }

    private function calculateAverage(array $values)
    {
        $nonNullValues = array_filter($values, fn($v) => !is_null($v));
        return count($nonNullValues) 
            ? array_sum($nonNullValues) / count($nonNullValues) 
            : null;
    }

    private function calculateSum(array $values)
    {
        return array_reduce($values, fn($carry, $v) => $carry + ($v ?? 0), 0);
    }
}

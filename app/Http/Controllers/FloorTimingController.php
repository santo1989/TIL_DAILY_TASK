<?php

namespace App\Http\Controllers;

use App\Imports\FloorTimingImport;
use App\Models\FloorTiming;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FloorTimingController extends Controller
{
    public function index()
    {
        // Filter by report date if provided
        $reportDate = request('report_date');
        if ($reportDate) {
            $timings = FloorTiming::whereDate('report_date', $reportDate)
                ->latest('report_date')
                ->paginate(10);
        } else {
            // Default to latest report date
            $timings = FloorTiming::latest('report_date')
                ->paginate(10);
        }

        return view('backend.library.floor-timings.index', compact('timings', 'reportDate'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls',
            'report_date' => 'required|date'
        ]);

        Excel::import(
            new FloorTimingImport($request->report_date),
            $request->file('excel_file')
        );

        return back()->with('success', 'Floor timings imported successfully');
    }

    public function downloadTemplate()
    {
        $filePath = public_path('templates\Floor_Starting_Clossing_Time.xlsx');
        return response()->download($filePath);
    }

    public function edit(FloorTiming $timing)
    {
        return view('backend.library.floor-timings.edit', compact('timing'));
    }

    public function update(Request $request, FloorTiming $timing)
    {
        $validated = $request->validate([
            'starting_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:starting_time',
            'starting_responsible' => 'required|string',
            'closing_responsible' => 'required|string',
            'remarks' => 'nullable|string'
        ]);

        $timing->update([
            'starting_time' => $validated['starting_time'],
            'closing_time' => $validated['closing_time'],
            'starting_responsible' => $this->parseResponsible($validated['starting_responsible']),
            'closing_responsible' => $this->parseResponsible($validated['closing_responsible']),
            'remarks' => $validated['remarks']
        ]);

        return redirect()->route('floor-timings.index')
            ->with('success', 'Timing updated successfully');
    }

    private function parseResponsible($input)
    {
        return collect(explode(', ', $input))
            ->map(function ($item) {
                preg_match('/(.*?)\s*\((.*?)\)/', $item, $matches);
                return [
                    'name' => $matches[1] ?? $item,
                    'role' => $matches[2] ?? 'Unknown'
                ];
            });
    }

    public function destroy(FloorTiming $timing)
    {
        $timing->delete();
        return back()->with('success', 'Timing deleted successfully');
    }
}

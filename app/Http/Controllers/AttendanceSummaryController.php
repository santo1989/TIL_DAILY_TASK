<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSummary;
use Illuminate\Http\Request;
use App\Imports\AttendanceSummaryImport;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceSummaryController extends Controller
{


    public function uploadAttendanceSummary(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls',
            'report_date' => 'sometimes|date'
        ]);

        try {
            $reportDate = $request->input('report_date') ?? now()->format('Y-m-d');

            Excel::import(new AttendanceSummaryImport($reportDate), $request->file('excel_file'));

            return back()->withMessage('Attendance summary imported successfully');
        } catch (\Exception $e) {
            return back()->withErrors('Error importing file: ' . $e->getMessage());
        }
    }
    // Show upload form and existing data
    public function index()
    {
        $attendanceData = AttendanceSummary::latest('report_date')
            ->paginate(10);
     
            $todayTotal = $attendanceData
                ->where('report_date', now()->format('Y-m-d'))
                ->first();
            //calculate the total for today onroll, present, absent, leave, ml from the attendanceData data
            $todayTotal = $attendanceData
                ->where('report_date', now()->format('Y-m-d'))
                ->first();
            $todayTotal = [];
            $todayTotal['onroll'] = $attendanceData->sum('onroll');
            $todayTotal['present'] = $attendanceData->sum('present');
            $todayTotal['absent'] = $attendanceData->sum('absent');
            $todayTotal['leave'] = $attendanceData->sum('leave');
            $todayTotal['ml'] = $attendanceData->sum('ml');

        // return view('attendance-summary.index', compact('attendanceData'));
        return view('backend.library.attendance-summary.index', compact('attendanceData', 'todayTotal'));
    }

    // Handle file upload
    public function upload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xls,xlsx',
            'report_date' => 'sometimes|date'
        ]);

        try {
            $reportDate = $request->report_date ?? now()->format('Y-m-d');

            Excel::import(
                new AttendanceSummaryImport($reportDate),
                $request->file('excel_file')
            );

            return redirect()->back()
                ->withMessage('Attendance data uploaded successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors('Error uploading file: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $filePath = public_path('downloads/1. Attendence Summary.xls');

        if (file_exists($filePath)) {
            return response()->download($filePath);
        }

        return redirect()->back()
            ->with('error', 'Template file not found');
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }

   
    public function show(AttendanceSummary $attendanceSummary)
    {
        //
    }

    public function edit(AttendanceSummary $attendanceSummary)
    {
        return view('backend.library.attendance-summary.edit', compact('attendanceSummary'));
    }

    public function update(Request $request, AttendanceSummary $attendanceSummary)
    {
        $validated = $request->validate([
            'report_date' => 'required|date',
            'floor' => 'required|string|unique:attendance_summaries,floor,' . $attendanceSummary->id . ',id,report_date,' . $request->report_date,
            'onroll' => 'required|integer|min:0',
            'present' => 'required|integer|min:0',
            'absent' => 'required|integer|min:0',
            'leave' => 'required|integer|min:0',
            'ml' => 'required|integer|min:0',
            'remarks' => 'nullable|string'
        ]);

        try {
            $attendanceSummary->update($validated);
            return redirect()->route('attendance.summary')
                ->withMessage('Record updated successfully');
        } catch (\Exception $e) {
            return back()->withErrors('Error updating record: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, AttendanceSummary $attendanceSummary)
    {
        // Validate the request
        

        // Delete the records
        $ids = $attendanceSummary->id;
        AttendanceSummary::where('id', $ids)->delete();
        return redirect()->route('attendance.summary')
            ->withMessage('Records deleted successfully');
        
    }
}

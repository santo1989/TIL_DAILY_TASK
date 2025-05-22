<?php

namespace App\Http\Controllers;

use App\Imports\AttendanceReportImport;
use App\Models\AttendanceReport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceReportController extends Controller
{
    public function index()
    {
        // floor and employee_id search have data then search or else show all
        $query = AttendanceReport::query();
        if (request('floor')) {
            $query->where('floor', request('floor'));
        }
        if (request('employee_id')) {
            $query->where('employee_id', request('employee_id'));
        }
        if (request('floor') && request('employee_id')) {
            $query->where('floor', request('floor'))
                ->where('employee_id', request('employee_id'));
        }
        $reports = $query->latest('report_date')->paginate(20);
        $reports->appends(request()->query());

        $floors = request('floor') ? request('floor') : null;
        $employee_ids = request('employee_id') ? request('employee_id') : null;


        // $reports = AttendanceReport::latest('report_date')
        //     // ->paginate(20);

        return view('backend.library.attendance-reports.index', compact('reports', 'floors', 'employee_ids'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls',
            'report_date' => 'sometimes|date'
        ]);

        Excel::import(
            new AttendanceReportImport($request->report_date),
            $request->file('excel_file')
        );

        return back()->with('success', 'Reports imported successfully');
    }
    // downloadTemplate
    public function downloadTemplate()
    {
        $filePath = public_path('downloads/2. Come Back Report.xls');

        if (file_exists($filePath)) {
            return response()->download($filePath);
        }

        return redirect()->back()
            ->with('error', 'Template file not found');
    }
    public function edit(AttendanceReport $report)
    {
        return view('backend.library.attendance-reports.edit', compact('report'));
    }

    public function update(Request $request, AttendanceReport $report)
    {
        $validated = $request->validate([
            'employee_id' => 'required',
            'name' => 'required',
            'designation' => 'required',
            'floor' => 'nullable|string',
            'in_time' => 'nullable|date_format:H:i',
            'reason' => 'nullable|string',
            'remarks' => 'nullable|string'
        ]);

        $report->update($validated);
        return redirect()->route('attendance-reports.index')
            ->with('success', 'Record updated successfully');
    }

    public function destroy(AttendanceReport $report)
    {
        $report->delete();
        return back()->with('success', 'Record deleted successfully');
    }

    
    public function create()
    {
        //
    }

    
    public function store(Request $request)
    {
        //
    }

     
    public function show(AttendanceReport $attendanceReport)
    {
        //
    }

}

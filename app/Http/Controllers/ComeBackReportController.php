<?php

namespace App\Http\Controllers;

use App\Models\ComeBackReport;
use Illuminate\Http\Request;
use App\Imports\ComeBackReportImport;
use Maatwebsite\Excel\Facades\Excel;

class ComeBackReportController extends Controller
{
    public function comebackreportsupload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls',
            'report_date' => 'sometimes|date'
        ]);

        try {
            $reportDate = $request->input('report_date') ?? now()->format('Y-m-d');
            Excel::import(new ComeBackReportImport($reportDate), $request->file('excel_file'));
            return back()->withMessage('Report imported successfully');
        } catch (\Exception $e) {
            return back()->withErrors('Error importing file: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $filePath = public_path('downloads/2. Come Back Report.xls');

        if (file_exists($filePath)) {
            return response()->download($filePath);
        }

        return redirect()->back()
            ->with('error', 'Template file not found');
    }

    public function index()
    {
        $reports = ComeBackReport::latest('report_date')->paginate(20);
        return view('backend.library.comeback-reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ComeBackReport  $comeBackReport
     * @return \Illuminate\Http\Response
     */
    public function show(ComeBackReport $comeBackReport)
    {
        //
    }

    public function edit($id)
    {
        $report = ComeBackReport::findOrFail($id);
        return view('backend.library.comeback-reports.edit', compact('report'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'report_date' => 'required|date',
            'employee_id' => 'required|string',
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'floor' => 'required|string|max:255',
            'absent_days' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
            'councilor_name' => 'required|string|max:255',
            'remarks' => 'nullable|string'
        ]);

        try {
            $report = ComeBackReport::findOrFail($id);
            $report->update($validated);

            return redirect()->route('comeback.reports')
                ->with('message', 'Record updated successfully');
        } catch (\Exception $e) {
            return back()->withErrors('Error updating record: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        // dd($id);
        try {
            $report = ComeBackReport::findOrFail($id);
            $report->delete();

            return redirect()->route('comeback.reports')
                ->withMessage('Record deleted successfully');
        } catch (\Exception $e) {
            return back()->withErrors('Error deleting record: ' . $e->getMessage());
        }
    }
}

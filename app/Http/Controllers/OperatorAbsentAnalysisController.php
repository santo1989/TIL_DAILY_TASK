<?php

namespace App\Http\Controllers;


use App\Models\OperatorAbsentAnalysis;
use App\Imports\OperatorAbsentAnalysisImport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OperatorAbsentAnalysisController extends Controller
{
    public function index()
    {
        // floor and employee_id search have data then search or else show all
        $query = OperatorAbsentAnalysis::query();
        if (request('floor')) {
            $query->where('floor', request('floor'));
        }
        if (request('employee_id')) {
            $query->where('employee_id', request('employee_id'));
        }
        if(request('floor') && request('employee_id')){
            $query->where('floor', request('floor'))
                ->where('employee_id', request('employee_id'));
        }
        $reports = $query->latest('report_date')->paginate(20);
        $reports->appends(request()->query());

        $floors= request('floor') ? request('floor') : null;
        $employee_ids= request('employee_id') ? request('employee_id') : null;
      
        return view('backend.library.operator-absent-analysis.index', compact('reports', 'floors', 'employee_ids'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls',
            'report_date' => 'sometimes|date'
        ]);

        try {
            $reportDate = $request->input('report_date') ?? now()->format('Y-m-d');
            Excel::import(new OperatorAbsentAnalysisImport($reportDate), $request->file('excel_file'));
            return back()->withMessage( 'Report imported successfully');
        } catch (\Exception $e) {
            return back()->withErrors( 'Error importing file: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $report = OperatorAbsentAnalysis::findOrFail($id);
        //all dates are converted to Y-m-d format using carbon
        $report->join_date = Carbon::parse($report->join_date)->format('Y-m-d');
        $report->last_p_date = Carbon::parse($report->last_p_date)->format('Y-m-d');
        $report->come_back = $report->come_back ? Carbon::parse($report->come_back)->format('Y-m-d') : null;
        $report->report_date = Carbon::parse($report->report_date)->format('Y-m-d'); 
     
        
        return view('backend.library.operator-absent-analysis.edit', compact('report'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'floor' => 'required|string',
            'employee_id' => 'required|string',
            'name' => 'required|string',
            'designation' => 'required|string',
            'join_date' => 'required|date',
            'line' => 'required|integer',
            'total_absent_days' => 'required|integer|min:0',
            'last_p_date' => 'required|date',
            'absent_reason' => 'required|string',
            'come_back' => 'nullable|date',
            'remarks' => 'nullable|string'
        ]);

        $report = OperatorAbsentAnalysis::findOrFail($id);
        $report->update($validated);

        return redirect()->route('operator-absent-analysis.index')
            ->withMessage( 'Record updated successfully');
    }

    public function destroy($id)
    {
        $report = OperatorAbsentAnalysis::findOrFail($id);
        $report->delete();

        return redirect()->route('operator-absent-analysis.index')
            ->withMessage( 'Record deleted successfully');
    }

    // operator-absent-analysis.download.template

    public function downloadTemplate()
    {
        $filePath = public_path('downloads/3. operator_absent_analysis_template.xls');

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
     * @param  \App\Models\OperatorAbsentAnalysis  $operatorAbsentAnalysis
     * @return \Illuminate\Http\Response
     */
    public function show(OperatorAbsentAnalysis $operatorAbsentAnalysis)
    {
        //
    }

}

<?php

namespace App\Http\Controllers;
use App\Models\DailyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 

class DailyReportController extends Controller
{
    public function index()
    {
       
        // Filter by report date if provided
        $reportDate = request('report_date');
        if ($reportDate) {
            $reports = DailyReport::whereDate('report_date', $reportDate)
                ->latest('report_date')
                ->paginate(10);
        } else {
            // Default to latest report date
            $reports = DailyReport::latest('report_date')
                ->paginate(10);
        }
        return view('backend.library.daily-reports.index', compact('reports', 'reportDate'));
        
    }

    public function create()
    {
        return view('backend.library.daily-reports.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'report_date' => 'required|date|unique:daily_reports',
            'shipment_date.*' => 'required|date',
            'export_qty.*' => 'required|integer',
            'export_value.*' => 'required|numeric',
            'dhu_floor.*' => 'required|string',
            'dhu_percentage.*' => 'required|numeric',
            'remarkable_incident' => 'nullable|string',
            'improvement_area' => 'nullable|string',
            'other_information' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $shipments = [];
        foreach ($request->shipment_date as $key => $date) {
            $shipments[] = [
                'date' => $date,
                'export_qty' => $request->export_qty[$key],
                'export_value' => $request->export_value[$key]
            ];
        }

        $dhuReports = [];
        foreach ($request->dhu_floor as $key => $floor) {
            $dhuReports[] = [
                'floor' => $floor,
                'dhu_percentage' => $request->dhu_percentage[$key]
            ];
        }

        DailyReport::create([
            'report_date' => $request->report_date,
            'shipments' => $shipments,
            'dhu_reports' => $dhuReports,
            'remarkable_incident' => $request->remarkable_incident,
            'improvement_area' => $request->improvement_area,
            'other_information' => $request->other_information
        ]);

        return redirect()->route('daily-reports.index')
            ->with('success', 'Daily report created successfully');
    }

    public function edit(DailyReport $report)
    {
        return view('backend.library.daily-reports.edit', compact('report'));
    }

    public function update(Request $request, DailyReport $report)
    {
        $validator = Validator::make($request->all(), [
            'report_date' => 'required|date|unique:daily_reports,report_date,' . $report->id,
            'shipment_date.*' => 'required|date',
            'export_qty.*' => 'required|integer',
            'export_value.*' => 'required|numeric',
            'dhu_floor.*' => 'required|string',
            'dhu_percentage.*' => 'required|numeric',
            'remarkable_incident' => 'nullable|string',
            'improvement_area' => 'nullable|string',
            'other_information' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $shipments = [];
        foreach ($request->shipment_date as $key => $date) {
            $shipments[] = [
                'date' => $date,
                'export_qty' => $request->export_qty[$key],
                'export_value' => $request->export_value[$key]
            ];
        }

        $dhuReports = [];
        foreach ($request->dhu_floor as $key => $floor) {
            $dhuReports[] = [
                'floor' => $floor,
                'dhu_percentage' => $request->dhu_percentage[$key]
            ];
        }

        $report->update([
            'report_date' => $request->report_date,
            'shipments' => $shipments,
            'dhu_reports' => $dhuReports,
            'remarkable_incident' => $request->remarkable_incident,
            'improvement_area' => $request->improvement_area,
            'other_information' => $request->other_information
        ]);

        return redirect()->route('daily-reports.index')
            ->with('success', 'Daily report updated successfully');
    }

    public function destroy(DailyReport $report)
    {
        $report->delete();
        return back()->with('success', 'Daily report deleted successfully');
    }

}

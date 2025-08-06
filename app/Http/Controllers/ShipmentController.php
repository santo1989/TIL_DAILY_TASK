<?php

namespace App\Http\Controllers;

use App\Imports\ShipmentImport;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ShipmentController extends Controller
{
    public function index()
    {
        $query = Shipment::query();

        if (request('floor')) {
            $query->where('floor', request('floor'));
        }

        if (request('report_date')) {
            $query->whereDate('report_date', request('report_date'));
        }

        $shipments = $query->latest('report_date')->paginate(10);

        return view('backend.library.shipments.index', compact('shipments'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls',
            'report_date' => 'required|date'
        ]);

        Excel::import(
            new ShipmentImport($request->report_date),
            $request->file('excel_file')
        );

        return back()->with('success', 'OT shipments imported successfully');
    }

    public function downloadTemplate()
    {
        $filePath = public_path('downloads/ot-achievement-template.xlsx');
        return response()->download($filePath);
    }

    public function create()
    {
        return view('backend.library.shipments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipment_date' => 'required|array|min:1',
            'shipment_date.*' => 'required|date',
            'export_qty' => 'required|array|min:1',
            'export_qty.*' => 'required|numeric|min:0',
            'export_value' => 'required|array|min:1',
            'export_value.*' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',
            'report_date' => 'required|date'
        ]);

        //all dates should be in Y-m-d format
        $validated['shipment_date'] = array_map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        }, $validated['shipment_date']);
        $validated['report_date'] = \Carbon\Carbon::parse($validated['report_date'])->format('Y-m-d');  


        // Process each shipment in the arrays
        foreach ($validated['shipment_date'] as $index => $date) {
            Shipment::create([
                'shipment_date' => $date,
                'export_qty' => $validated['export_qty'][$index],
                'export_value' => $validated['export_value'][$index],
                'remarks' => $validated['remarks'] ?? null,
                'report_date' => $validated['report_date']
            ]);
        }

        return redirect()->route('shipments.index')
            ->with('success', 'Shipments created successfully');
    }

    public function edit(Shipment $shipment)
    {
        return view('backend.library.shipments.edit', compact('shipment'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        // dd($shipment);
        // Validate the request data
        $validated = $request->validate([
            'shipment_date' => 'required|array|min:1',
            'shipment_date.*' => 'required|date',
            'export_qty' => 'required|array|min:1',
            'export_qty.*' => 'required|numeric|min:0',
            'export_value' => 'required|array|min:1',
            'export_value.*' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',
            'report_date' => 'required|date'
        ]);

        // Format dates to Y-m-d
        $validated['shipment_date'] = array_map(function ($date) {
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        }, $validated['shipment_date']);
        $validated['report_date'] = \Carbon\Carbon::parse($validated['report_date'])->format('Y-m-d');

        // Update the shipment record for each item
        foreach ($validated['shipment_date'] as $index => $date) {
            $shipment->update([
                'shipment_date' => $date,
                'export_qty' => $validated['export_qty'][$index],
                'export_value' => $validated['export_value'][$index],
                'remarks' => $validated['remarks'] ?? null,
                'report_date' => $validated['report_date']
            ]);
        }

        return redirect()->route('shipments.index')
            ->with('success', 'Record updated successfully');
    }

    public function destroy(Shipment $shipment)
    {
        $shipment->delete();
        return back()->with('success', 'Record deleted successfully');
    }
}

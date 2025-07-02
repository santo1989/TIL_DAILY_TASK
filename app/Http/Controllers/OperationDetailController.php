<?php

namespace App\Http\Controllers;

use App\Models\OperationDetail;
use Illuminate\Http\Request;
use App\Imports\OperationDetailImport;
use Maatwebsite\Excel\Facades\Excel;

class OperationDetailController extends Controller
{
    public function index()
    {
        $query = OperationDetail::query();

        if (request('report_date')) {
            $query->whereDate('report_date', request('report_date'));
        }

        $details = $query->latest('report_date')->paginate(20);
        return view('backend.library.operations.index', compact('details'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls',
            'report_date' => 'required|date'
        ]);

        Excel::import(
            new OperationDetailImport($request->report_date),
            $request->file('excel_file')
        );

        return back()->with('success', 'Operation details imported successfully');
    }

    public function downloadTemplate()
    {
        $filePath = public_path('downloads/operation_details_template.xlsx');
        return response()->download($filePath);
    }

    

    public function edit(OperationDetail $detail)
    {
        return view('backend.library.operations.edit', compact('detail'));
    }

    public function update(Request $request, OperationDetail $detail)
    {
        $validated = $request->validate([
            'floor_1' => 'nullable|numeric',
            'floor_2' => 'nullable|numeric',
            'floor_3' => 'nullable|numeric',
            'floor_4' => 'nullable|numeric',
            'floor_5' => 'nullable|numeric',
            'result' => 'nullable|numeric',
            'remarks' => 'nullable|string'
        ]);

        $detail->update($validated);
        return redirect()->route('operation-details.index')
            ->with('success', 'Record updated successfully');
    }

    public function destroy(OperationDetail $detail)
    {
        $detail->delete();
        return back()->with('success', 'Record deleted successfully');
    }
}

<?php

namespace App\Http\Controllers;

use App\Imports\OtAchievementImport;
use App\Models\OtAchievement;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OtAchievementController extends Controller
{
    public function index()
    {
        $query = OtAchievement::query();

        if (request('floor')) {
            $query->where('floor', request('floor'));
        }

        if (request('report_date')) {
            $query->whereDate('report_date', request('report_date'));
        }

        $achievements = $query->latest('report_date')->paginate(10);

        return view('backend.library.ot-achievements.index', compact('achievements'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls',
            'report_date' => 'required|date'
        ]);

        Excel::import(
            new OtAchievementImport($request->report_date),
            $request->file('excel_file')
        );

        return back()->with('success', 'OT achievements imported successfully');
    }

    public function downloadTemplate()
    {
        $filePath = public_path('downloads/ot-achievement-template.xlsx');
        return response()->download($filePath);
    }

    public function edit(OtAchievement $achievement)
    {
        return view('backend.library.ot-achievements.edit', compact('achievement'));
    }

    public function update(Request $request, OtAchievement $achievement)
    {
        $validated = $request->validate([
            'two_hours_ot_persons' => 'required|integer',
            'above_two_hours_ot_persons' => 'required|integer',
            'achievement' => 'required|numeric',
            'remarks' => 'nullable|string'
        ]);

        $achievement->update($validated);
        return redirect()->route('ot-achievements.index')
            ->with('success', 'Record updated successfully');
    }

    public function destroy(OtAchievement $achievement)
    {
        $achievement->delete();
        return back()->with('success', 'Record deleted successfully');
    }
}

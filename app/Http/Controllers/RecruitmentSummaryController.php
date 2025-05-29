<?php

namespace App\Http\Controllers;

use App\Models\RecruitmentSummary;
use Illuminate\Http\Request;
use App\Imports\RecruitmentSummaryImport;
use Maatwebsite\Excel\Facades\Excel;

class RecruitmentSummaryController extends Controller
{
    public function index()
    {
        
        $query = RecruitmentSummary::query();

        // $all = RecruitmentSummary::all();
        // dd($all);

        if (request('designation')) {
            $query->where('designation', request('designation'));
        }

        if (request('floor')) {
            $query->where('test_taken_floor', request('floor'));
        }

        if (request('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('Candidate', 'like', "%{$search}%")
                  ->orWhere('designation', 'like', "%{$search}%")
                  ->orWhere('test_taken_by', 'like', "%{$search}%");
            });
        }

        //select yes / no
        if (request('selected')) {
            $selected = request('selected');
            if ($selected === 'yes') {
                $query->where('selected', 'yes');
            } elseif ($selected === 'no') {
                $query->where('selected', 'no');
            }
        }

        $summaries = $query->latest('interview_date')->paginate(20);
        $designations = RecruitmentSummary::distinct('designation')->pluck('designation');
        $floors = RecruitmentSummary::distinct('test_taken_floor')->pluck('test_taken_floor');
        // dd($summaries);
        return view('backend.library.recruitment_summaries.index',  compact('summaries', 'designations', 'floors'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls',
        ]);

        Excel::import(new RecruitmentSummaryImport, $request->file('excel_file'));

        return back()->with('success', 'Recruitment summaries imported successfully');
    }

    public function downloadTemplate()
    {
        $filePath = public_path('templates/Recruitment_Summary_Interview.xls');
        return response()->download($filePath);
    }

    public function edit(RecruitmentSummary $summary)
    {
        return view('backend.library.recruitment_summaries.edit', compact('summary'));
    }

    public function update(Request $request, RecruitmentSummary $summary)
    {
        $validated = $request->validate([
            'Candidate' => 'required|string',
            'designation' => 'required|string',
            'grade' => 'nullable|string',
            'salary' => 'required|numeric',
            'probable_date_of_joining' => 'required|date',
            'allocated_floor' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        $summary->update($validated);
        return redirect()->route('recruitment-summaries.index')
            ->with('success', 'Record updated successfully');
    }

    public function destroy(RecruitmentSummary $summary)
    {
        $summary->delete();
        return back()->with('success', 'Record deleted successfully');
    }
}

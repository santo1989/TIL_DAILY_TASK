<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceReport;
use App\Models\AttendanceSummary;
use App\Models\ComeBackReport;
use App\Models\DailyReport;
use App\Models\OperationDetail;
use App\Models\OperatorAbsentAnalysis;
use App\Models\Shipment;
use App\Models\RecruitmentSummary;

class ReportController extends Controller
{

    public function todayReport()
    {
        //if filter by report date is provided, use it; otherwise, use today's date
        $reportDate = request('report_date');
        if ($reportDate) {
            $today = $reportDate;
        } else {
            $today = now()->format('Y-m-d');
        }

        return view('reports.todayReport', [
            'attendanceSummary' => AttendanceSummary::where('report_date', $today)->get(),
            'comeBackReports' => ComeBackReport::where('report_date', $today)->latest()->take(4)->get(),
            'absentAnalyses' => OperatorAbsentAnalysis::where('report_date', $today)->latest()->take(4)->get(),
            'attendanceReports' => AttendanceReport::where('report_date', $today)->get(),
            'recruitmentSummary' => RecruitmentSummary::whereDate('interview_date', $today)->get(),
            'operationDetails' => OperationDetail::where('report_date', $today)->get(),
            'otAchievements' => Shipment::where('report_date', $today)->get(),
            'dailyReport' => DailyReport::where('report_date', $today)->first(),
            'reportDate' => $today
        ]);
    }

    public function todayGraph()
    {
        //if filter by report date is provided, use it; otherwise, use today's date
        $reportDate = request('report_date');
        if ($reportDate) {
            $today = $reportDate;
        } else {
            $today = now()->format('Y-m-d');
        }

        return view('reports.todayGraph', [
            'attendanceSummary' => AttendanceSummary::where('report_date', $today)->get(),
            'comeBackReports' => ComeBackReport::where('report_date', $today)->latest()->take(4)->get(),
            'absentAnalyses' => OperatorAbsentAnalysis::where('report_date', $today)->latest()->take(4)->get(),
            'attendanceReports' => AttendanceReport::where('report_date', $today)->get(),
            'recruitmentSummary' => RecruitmentSummary::whereDate('interview_date', $today)->get(),
            'operationDetails' => OperationDetail::where('report_date', $today)->get(),
            'otAchievements' => Shipment::where('report_date', $today)->get(),
            'dailyReport' => DailyReport::where('report_date', $today)->first(),
            'reportDate' => $today
        ]);
    }

    public function Report()
    {
        //if filter by report date is provided, use it; otherwise, use today's date
        $reportDate = request('report_date');
        if ($reportDate) {
            $today = $reportDate;
        } else {
            $today = now()->format('Y-m-d');
        }

        return view('reports.report', [
            'attendanceSummary' => AttendanceSummary::where('report_date', $today)->get(),
            'comeBackReports' => ComeBackReport::where('report_date', $today)->get(),
            'absentAnalyses' => OperatorAbsentAnalysis::where('report_date', $today)->get(),
            'attendanceReports' => AttendanceReport::where('report_date', $today)->get(),
            'recruitmentSummary' => RecruitmentSummary::whereDate('interview_date', $today)->get(),
            'operationDetails' => OperationDetail::where('report_date', $today)->get(),
            'otAchievements' => Shipment::where('report_date', $today)->get(),
            'dailyReport' => DailyReport::where('report_date', $today)->first(),
            'reportDate' => $today
        ]);
    }

    public function fullDashboard()
    {
        $reportDate = request('report_date', now()->format('Y-m-d'));

        return view('reports.full_dashboard', [
            'attendanceSummary' => AttendanceSummary::where('report_date', $reportDate)->get(),
            'comeBackReports' => ComeBackReport::where('report_date', $reportDate)->get(),
            'absentAnalyses' => OperatorAbsentAnalysis::where('report_date', $reportDate)->get(),
            'attendanceReports' => AttendanceReport::where('report_date', $reportDate)->get(),
            'recruitmentSummary' => RecruitmentSummary::whereDate('interview_date', $reportDate)->get(),
            'operationDetails' => OperationDetail::where('report_date', $reportDate)->get(),
            'otAchievements' => Shipment::where('report_date', $reportDate)->get(),
            'dailyReport' => DailyReport::where('report_date', $reportDate)->first(),
            'reportDate' => $reportDate
        ]);
    }

    public function summaryDashboard()
    {
        $reportDate = request('report_date', now()->format('Y-m-d'));

        return view('reports.summary_dashboard', [
            'attendanceSummary' => AttendanceSummary::where('report_date', $reportDate)->get(),
            'comeBackReports' => ComeBackReport::where('report_date', $reportDate)->get(),
            'absentAnalyses' => OperatorAbsentAnalysis::where('report_date', $reportDate)->get(),
            'recruitmentSummary' => RecruitmentSummary::whereDate('interview_date', $reportDate)->get(),
            'operationDetails' => OperationDetail::where('report_date', $reportDate)->get(),
            'otAchievements' => Shipment::where('report_date', $reportDate)->get(),
            'dailyReport' => DailyReport::where('report_date', $reportDate)->first(),
            'reportDate' => $reportDate
        ]);
    }

    public function graphicalDashboard()
    {
        $reportDate = request('report_date', now()->format('Y-m-d'));

        return view('reports.graphical_dashboard', [
            'attendanceSummary' => AttendanceSummary::where('report_date', $reportDate)->get(),
            'comeBackReports' => ComeBackReport::where('report_date', $reportDate)->get(),
            'absentAnalyses' => OperatorAbsentAnalysis::where('report_date', $reportDate)->get(),
            'recruitmentSummary' => RecruitmentSummary::whereDate('interview_date', $reportDate)->get(),
            'operationDetails' => OperationDetail::where('report_date', $reportDate)->get(),
            'otAchievements' => Shipment::where('report_date', $reportDate)->get(),
            'dailyReport' => DailyReport::where('report_date', $reportDate)->first(),
            'reportDate' => $reportDate
        ]);
    }
}

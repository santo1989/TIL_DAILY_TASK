<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Same head content as original report -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tosrifa Industries - Full Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/file-saver/dist/FileSaver.min.js"></script>
    <style>
        :root {
            --primary: #1a3a5f;
            --secondary: #2c6eb5;
            --success: #27ae60;
            --warning: #f39c12;
            --danger: #e74c3c;
            --light: #f8f9fa;
            --dark: #2c3e50;
            --gray: #95a5a6;
            --border: #dee2e6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: #333;
            padding: 20px;
        }

        .dashboard {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .header {
            background: var(--primary);
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .company-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .company-info i {
            font-size: 32px;
        }

        .company-name {
            font-size: 24px;
            font-weight: 600;
        }

        .report-title {
            font-size: 18px;
            opacity: 0.9;
            margin-top: 5px;
        }

        .controls {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .date-filter {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .date-filter input {
            padding: 8px 12px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 4px;
            font-size: 14px;
        }

        .date-filter input:focus {
            outline: none;
            border-color: white;
        }

        .date-filter input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .btn {
            padding: 8px 16px;
            background: white;
            color: var(--primary);
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .btn:hover {
            background: #f0f5ff;
            transform: translateY(-2px);
        }

        .btn-download {
            background: var(--success);
            color: white;
        }

        .btn-download:hover {
            background: #219653;
        }

        .content {
            padding: 25px 30px;
        }

        .section {
            margin-bottom: 30px;
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
        }

        .section-header {
            background: #e9f0f7;
            padding: 12px 20px;
            border-bottom: 1px solid var(--border);
            font-weight: 600;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-content {
            padding: 20px;
            background: white;
        }

        .table-container {
            overflow-x: auto;
            border: 1px solid var(--border);
            border-radius: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid var(--border);
        }

        th {
            background-color: #f1f7fd;
            color: var(--primary);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
        }

        tr:nth-child(even) {
            background-color: #f9fbfd;
        }

        tr:hover {
            background-color: #edf5ff;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .summary-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }

        .summary-value {
            font-size: 28px;
            font-weight: 700;
            margin: 10px 0;
        }

        .summary-label {
            font-size: 14px;
            color: var(--gray);
        }

        .kpi-primary {
            color: var(--primary);
        }

        .kpi-success {
            color: var(--success);
        }

        .kpi-warning {
            color: var(--warning);
        }

        .kpi-danger {
            color: var(--danger);
        }

        .floor-tabs {
            display: flex;
            gap: 5px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .floor-tab {
            padding: 8px 15px;
            background: #e9ecef;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
        }

        .floor-tab.active {
            background: var(--primary);
            color: white;
        }

        .floor-content {
            display: none;
        }

        .floor-content.active {
            display: block;
        }

        .charts-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 30px 0;
        }

        .chart-container {
            background: white;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 20px;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .chart-title {
            font-weight: 600;
            color: var(--primary);
        }

        .chart-actions {
            display: flex;
            gap: 10px;
        }

        .chart {
            height: 300px;
            position: relative;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: var(--gray);
            font-size: 14px;
            border-top: 1px solid var(--border);
        }

        @media (max-width: 992px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
            }

            .controls {
                width: 100%;
                justify-content: space-between;
            }

            .charts-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .date-filter {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="header">
            <div class="company-info">
                <i class="fas fa-industry"></i>
                <div>
                    <div class="company-name">Tosrifa Industries Ltd.</div>
                    <div class="report-title">Comprehensive Daily Report - {{ \Carbon\Carbon::parse($reportDate)->format('d/m/Y') }}</div>
                </div>
            </div>
            <div class="controls">
                <form method="GET" action="{{ route('dashboard.full') }}" class="date-filter">
                    <input type="date" name="report_date" id="reportDate" value="{{ $reportDate }}">
                    <button type="submit" class="btn">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </form>
                <button class="btn btn-download" id="downloadBtn">
                    <i class="fas fa-download"></i> Download Excel
                </button>
            </div>
        </div>

        <div class="content">
            <!-- Attendance Summary Section -->
            <div class="section">
                <div class="section-header">
                    <i class="fas fa-users"></i>
                    <span>Attendance Summary</span>
                </div>
                <div class="section-content">
                    <!-- Full table implementation -->
                </div>
            </div>

            <!-- Come Back Report Section -->
            <div class="section">
                <div class="section-header">
                    <i class="fas fa-user-check"></i>
                    <span>Come Back Report</span>
                </div>
                <div class="section-content">
                    <!-- Full table implementation -->
                </div>
            </div>

            <!-- Include all other sections with full tables -->
            
            <!-- Daily Report Section -->
            @if ($dailyReport)
                <div class="section">
                    <!-- Full daily report implementation -->
                </div>
            @endif
        </div>
    </div>
    
    <!-- Same JavaScript as original report -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Floor tabs functionality
            document.querySelectorAll('.floor-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    const floor = this.getAttribute('data-floor');

                    // Remove active class from all tabs
                    document.querySelectorAll('.floor-tab').forEach(t => {
                        t.classList.remove('active');
                    });

                    // Add active class to clicked tab
                    this.classList.add('active');

                    // Hide all floor contents
                    document.querySelectorAll('.floor-content').forEach(content => {
                        content.classList.remove('active');
                    });

                    // Show selected floor content
                    document.querySelector(`.floor-content[data-floor="${floor}"]`).classList.add(
                        'active');
                });
            });

            document.getElementById('downloadBtn').addEventListener('click', function() {
                // Create Excel workbook
                const workbook = new ExcelJS.Workbook();
                const worksheet = workbook.addWorksheet('Daily Incident Report');

                // Add report header
                worksheet.addRow(['Tosrifa Industries Ltd.']);
                worksheet.addRow([
                    'Daily Incident Report as on {{ \Carbon\Carbon::parse($reportDate)->format('d/m/Y') }}'
                ]);
                worksheet.addRow([]); // Empty row

                // 1. Attendance Summary
                worksheet.addRow(['1. Attendance Summary']);
                worksheet.addRow(['Floor', 'Onroll', 'Present', 'Absent', 'Leave', 'M/L', 'Remarks']);
                @foreach ($attendanceSummary as $summary)
                    worksheet.addRow([
                        '{{ $summary->floor }}',
                        {{ $summary->onroll }},
                        {{ $summary->present }},
                        {{ $summary->absent }},
                        {{ $summary->leave }},
                        {{ $summary->ml }},
                        '{{ $summary->remarks }}'
                    ]);
                @endforeach
                worksheet.addRow([
                    'Total',
                    {{ $attendanceSummary->sum('onroll') }},
                    {{ $attendanceSummary->sum('present') }},
                    {{ $attendanceSummary->sum('absent') }},
                    {{ $attendanceSummary->sum('leave') }},
                    {{ $attendanceSummary->sum('ml') }},
                    ''
                ]);
                worksheet.addRow([]); // Empty row

                // 2. Come Back Report
                worksheet.addRow(['2. Yesterday Absent but Today Present (Come Back) Report']);
                worksheet.addRow(['Sl', 'ID', 'Name', 'Designation', 'Floor', 'No of absent days',
                    'Reason for Absent', 'Councilor Name', 'Remarks'
                ]);
                @foreach ($comeBackReports as $index => $report)
                    worksheet.addRow([
                        {{ $index + 1 }},
                        '{{ $report->employee_id }}',
                        '{{ $report->name }}',
                        '{{ $report->designation }}',
                        '{{ $report->floor }}',
                        {{ $report->absent_days }},
                        '{{ $report->reason }}',
                        '{{ $report->councilor_name }}',
                        '{{ $report->remarks }}'
                    ]);
                @endforeach
                worksheet.addRow([]); // Empty row

                // 3. Operator Absent Analysis Report
                @php
                    $floors = $absentAnalyses->unique('floor')->pluck('floor')->filter();
                @endphp
                worksheet.addRow(['3. Operator Absent Analysis Report']);
                @foreach ($floors as $floor)
                    worksheet.addRow(['Floor: {{ $floor }}']);
                    worksheet.addRow(['Sl #', 'ID Card NO.', 'Name', 'Designation', 'Join Date',
                        'Last P. Date', 'Line', 'Total AB', 'Absent Reason', 'Come Back', 'Remarks'
                    ]);
                    @foreach ($absentAnalyses->where('floor', $floor) as $index => $analysis)
                        worksheet.addRow([
                            {{ $index + 1 }},
                            '{{ $analysis->employee_id }}',
                            '{{ $analysis->name }}',
                            '{{ $analysis->designation }}',
                            '{{ $analysis->join_date ? \Carbon\Carbon::parse($analysis->join_date)->format('Y-m-d') : '' }}',
                            '{{ $analysis->last_p_date ? \Carbon\Carbon::parse($analysis->last_p_date)->format('Y-m-d') : '' }}',
                            '{{ $analysis->line }}',
                            {{ $analysis->total_absent_days }},
                            '{{ $analysis->absent_reason }}',
                            '{{ $analysis->come_back ? \Carbon\Carbon::parse($analysis->come_back)->format('d.m.y') : '' }}',
                            '{{ $analysis->remarks }}'
                        ]);
                    @endforeach
                    worksheet.addRow([]); // Empty row after each floor
                @endforeach
                worksheet.addRow([]); // Empty row after section

                // 4. Staff & Executive's Status
                worksheet.addRow(['5. Staff & Executive\'s Status']);

                // 4.1 Late Comer
                worksheet.addRow(['5.1 Late Comer (Sr.Executive to Above)']);
                worksheet.addRow(['Sl', 'ID', 'Name', 'Designation', 'In Time', 'Remarks']);
                @foreach ($attendanceReports->where('type', 'late_comer') as $index => $report)
                    worksheet.addRow([
                        {{ $index + 1 }},
                        '{{ $report->employee_id }}',
                        '{{ $report->name }}',
                        '{{ $report->designation }}',
                        '{{ $report->in_time }}',
                        '{{ $report->remarks }}'
                    ]);
                @endforeach
                worksheet.addRow([]); // Empty row

                // 4.2 On Leave
                worksheet.addRow(['5.2 On Leave']);
                worksheet.addRow(['Sl', 'ID', 'Name', 'Designation', 'Floor', 'Reason of Leave',
                    'Remarks'
                ]);
                @foreach ($attendanceReports->where('type', 'on_leave') as $index => $report)
                    worksheet.addRow([
                        {{ $index + 1 }},
                        '{{ $report->employee_id }}',
                        '{{ $report->name }}',
                        '{{ $report->designation }}',
                        '{{ $report->floor }}',
                        '{{ $report->reason }}',
                        '{{ $report->remarks }}'
                    ]);
                @endforeach
                worksheet.addRow([]); // Empty row

                // 4.3 To be Absent
                worksheet.addRow(['5.3 To be Absent']);
                worksheet.addRow(['Sl', 'ID', 'Name', 'Designation', 'Floor', 'Reason of Absent',
                    'Remarks'
                ]);
                @foreach ($attendanceReports->where('type', 'to_be_absent') as $index => $report)
                    worksheet.addRow([
                        {{ $index + 1 }},
                        '{{ $report->employee_id }}',
                        '{{ $report->name }}',
                        '{{ $report->designation }}',
                        '{{ $report->floor }}',
                        '{{ $report->reason }}',
                        '{{ $report->remarks }}'
                    ]);
                @endforeach
                worksheet.addRow([]); // Empty row

                // 5. Recruitment Summary
                worksheet.addRow(['6. Recruitment Summary']);
                worksheet.addRow(['#', 'Candidate', 'Selected', 'Designation', 'Interview Date',
                    'Test Details', 'Salary', 'Joining Date'
                ]);
                @foreach ($recruitmentSummary as $summary)
                    @if ($summary->selected == 'Yes')
                        worksheet.addRow([
                            {{ $loop->iteration }},
                            '{{ $summary->Candidate }}',
                            '{{ $summary->selected }}',
                            '{{ $summary->designation }}',
                            '{{ $summary->interview_date ? \Carbon\Carbon::parse($summary->interview_date)->format('Y-m-d') : 'N/A' }}',
                            'Floor: {{ $summary->test_taken_floor }}\nBy: {{ $summary->test_taken_by }}',
                            '{{ $summary->salary }}',
                            '{{ $summary->probable_date_of_joining ? \Carbon\Carbon::parse($summary->probable_date_of_joining)->format('Y-m-d') : 'N/A' }}'
                        ]);
                    @endif
                @endforeach
                worksheet.addRow([]); // Empty row

                // 6. Operation Details
                worksheet.addRow(['8. Operation Details']);
                worksheet.addRow(['Activities', '1st Floor', '2nd Floor', '3rd Floor', '4th Floor',
                    '5th Floor', 'Total/Average', 'Remarks'
                ]);
                @foreach ($operationDetails as $operation)
                    worksheet.addRow([
                        '{{ $operation->activity }}',
                        {{ $operation->floor_1 }},
                        {{ $operation->floor_2 }},
                        {{ $operation->floor_3 }},
                        {{ $operation->floor_4 }},
                        {{ $operation->floor_5 }},
                        {{ $operation->result }},
                        '{{ $operation->remarks }}'
                    ]);
                @endforeach
                worksheet.addRow([]); // Empty row

                // 7. OT Achievements
                worksheet.addRow(['9. Details of O.T Achievement']);
                worksheet.addRow(['Floor', '2 Hours OT Persons', 'Above 2 Hours OT Persons', 'Achievement',
                    'Remarks'
                ]);
                @foreach ($otAchievements as $ot)
                    worksheet.addRow([
                        '{{ $ot->floor }}',
                        {{ $ot->two_hours_ot_persons }},
                        {{ $ot->above_two_hours_ot_persons }},
                        {{ number_format($ot->achievement, 2) }},
                        '{{ $ot->remarks }}'
                    ]);
                @endforeach
                worksheet.addRow([
                    'Total',
                    {{ $otAchievements->sum('two_hours_ot_persons') }},
                    {{ $otAchievements->sum('above_two_hours_ot_persons') }},
                    {{ $otAchievements->sum('achievement') }},
                    ''
                ]);

                //// Efficiency data show in table #
                worksheet.addRow([]); // Empty row
                worksheet.addRow(['Efficiency Data']);
                worksheet.addRow(['Floor', 'Efficiency (%)']);
                // Get efficiency data from operation details
                const efficiencyData = {!! json_encode($operationDetails->where('activity', 'Efficiency')->first()) !!};
                if (efficiencyData) {
                    worksheet.addRow([
                        '1st Floor',
                        efficiencyData.floor_1 ? (efficiencyData.floor_1 * 100).toFixed(2) : 0
                    ]);
                    worksheet.addRow([
                        '2nd Floor',
                        efficiencyData.floor_2 ? (efficiencyData.floor_2 * 100).toFixed(2) : 0
                    ]);
                    worksheet.addRow([
                        '3rd Floor',
                        efficiencyData.floor_3 ? (efficiencyData.floor_3 * 100).toFixed(2) : 0
                    ]);
                    worksheet.addRow([
                        '4th Floor',
                        efficiencyData.floor_4 ? (efficiencyData.floor_4 * 100).toFixed(2) : 0
                    ]);
                    worksheet.addRow([
                        '5th Floor',
                        efficiencyData.floor_5 ? (efficiencyData.floor_5 * 100).toFixed(2) : 0
                    ]);
                } else {
                    worksheet.addRow(['No efficiency data available']);
                }
                worksheet.addRow([]); // Empty row

                // 8. Daily Report & Remarks
                @if ($dailyReport)
                    worksheet.addRow(['Daily Report & Remarks']);
                    worksheet.addRow(['Shipments']);
                    worksheet.addRow(['Date', 'Export Qty', 'Export Value ($)']);

                    @foreach ($dailyReport->shipments as $shipment)
                        worksheet.addRow([
                            '{{ $shipment['date'] }}',
                            '{{ $shipment['export_qty'] }} pcs',
                            {{ $shipment['export_value'] }}
                        ]);
                    @endforeach

                    worksheet.addRow([]); // Empty row
                    worksheet.addRow(['Remarks']);

                    @if ($dailyReport->improvement_area)
                        worksheet.addRow(['Improvement Area:', '{{ $dailyReport->improvement_area }}']);
                    @endif

                    @if ($dailyReport->remarkable_incident)
                        worksheet.addRow(['Positive Note:', '{{ $dailyReport->remarkable_incident }}']);
                    @endif

                    @if ($dailyReport->other_information)
                        worksheet.addRow(['Other:', '{{ $dailyReport->other_information }}']);
                    @endif

                    worksheet.addRow([]); // Empty row
                @endif


                // Generate Excel file
                workbook.xlsx.writeBuffer().then(buffer => {
                    const blob = new Blob([buffer], {
                        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    });
                    saveAs(blob,
                        `Tosrifa_Daily_Report_{{ \Carbon\Carbon::parse($reportDate)->format('Y_m_d') }}.xlsx`
                    );
                });
            });


            // Initialize charts
            // Attendance Chart
            const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
            new Chart(attendanceCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($attendanceSummary->pluck('floor')) !!},
                    datasets: [{
                            label: 'Present',
                            data: {!! json_encode($attendanceSummary->pluck('present')) !!},
                            backgroundColor: '#27ae60',
                        },
                        {
                            label: 'Absent',
                            data: {!! json_encode($attendanceSummary->pluck('absent')) !!},
                            backgroundColor: '#e74c3c',
                        },
                        {
                            label: 'Leave',
                            data: {!! json_encode($attendanceSummary->pluck('leave')) !!},
                            backgroundColor: '#3498db',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            stacked: true,
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true
                        }
                    }
                }
            });

            // Efficiency Chart
            const efficiencyCtx = document.getElementById('efficiencyChart').getContext('2d');

            // Get efficiency data from operation details
            const efficiencyData = {!! json_encode($operationDetails->where('activity', 'Efficiency')->first()) !!};

            new Chart(efficiencyCtx, {
                type: 'line',
                data: {
                    labels: ['1st', '2nd', '3rd', '4th', '5th'],
                    datasets: [{
                        label: 'Efficiency (%)',
                        data: [
                            efficiencyData ? efficiencyData.floor_1 * 100 : 0,
                            efficiencyData ? efficiencyData.floor_2 * 100 : 0,
                            efficiencyData ? efficiencyData.floor_3 * 100 : 0,
                            efficiencyData ? efficiencyData.floor_4 * 100 : 0,
                            efficiencyData ? efficiencyData.floor_5 * 100 : 0
                        ],
                        borderColor: '#2c6eb5',
                        backgroundColor: 'rgba(44, 110, 181, 0.1)',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            min: 0,
                            max: 100
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
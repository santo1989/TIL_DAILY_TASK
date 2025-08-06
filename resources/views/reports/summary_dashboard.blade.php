<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tosrifa Industries - Summary Dashboard</title>
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
    <!-- Same CSS as original -->
    <style>
        /* Add these styles to existing CSS */
        .kpi-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .kpi-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            border-left: 4px solid var(--primary);
        }

        .kpi-card.danger {
            border-left-color: var(--danger);
        }

        .kpi-card.warning {
            border-left-color: var(--warning);
        }

        .kpi-card.success {
            border-left-color: var(--success);
        }

        .kpi-title {
            font-size: 16px;
            color: var(--gray);
            margin-bottom: 10px;
        }

        .kpi-value {
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
        }

        .kpi-subtext {
            font-size: 14px;
            color: var(--gray);
            margin-top: 5px;
        }

        .summary-section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border);
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
                    <div class="report-title">Summary Dashboard -
                        {{ \Carbon\Carbon::parse($reportDate)->format('d/m/Y') }}</div>
                </div>
            </div>
            <div class="controls">
                <form method="GET" action="{{ route('dashboard.summary') }}" class="date-filter">
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
        @php
            $totalOnroll = $attendanceSummary->sum('onroll');
            $totalPresent = $attendanceSummary->sum('present');
            $totalAbsent = $attendanceSummary->sum('absent');
            $totalLeave = $attendanceSummary->sum('leave');
        @endphp
        <div class="content">
            <div class="kpi-container">
                <div class="kpi-card">
                    <div class="kpi-title">Total Employees</div>
                    <div class="kpi-value">{{ $attendanceSummary->sum('onroll') }}</div>
                </div>

                <div class="kpi-card success">
                    <div class="kpi-title">Present Today</div>
                    <div class="kpi-value">{{ $totalPresent }}</div>
                    <div class="kpi-subtext">
                        {{ $totalOnroll > 0 ? number_format(($totalPresent / $totalOnroll) * 100, 1) : 0 }}% attendance
                    </div>
                </div>

                <div class="kpi-card danger">
                    <div class="kpi-title">Absent Today</div>
                    <div class="kpi-value">{{ $attendanceSummary->sum('absent') }}</div>
                    <div class="kpi-subtext">{{ $comeBackReports->count() }} returned from absence</div>
                </div>

                <div class="kpi-card warning">
                    <div class="kpi-title">On Leave</div>
                    <div class="kpi-value">{{ $attendanceSummary->sum('leave') }}</div>
                </div>
            </div>

            <div class="summary-section">
                <div class="section-title">
                    <i class="fas fa-user-clock"></i> Absenteeism Analysis
                </div>
                <div class="kpi-container">
                    <div class="kpi-card">
                        <div class="kpi-title">Long-term Absences</div>
                        <div class="kpi-value">{{ $absentAnalyses->where('total_absent_days', '>', 3)->count() }}</div>
                        <div class="kpi-subtext">>3 days absent</div>
                    </div>

                    <div class="kpi-card warning">
                        <div class="kpi-title">Most Absent Floor</div>
                        <div class="kpi-value">
                            @php
                                $floorAbsence = $attendanceSummary->sortByDesc('absent')->first();
                            @endphp
                            {{ $floorAbsence->floor ?? 'N/A' }}
                        </div>
                        <div class="kpi-subtext">{{ $floorAbsence->absent ?? 0 }} absences</div>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-title">New Recruits Today</div>
                        <div class="kpi-value">
                            {{ $recruitmentSummary->where('selected', 'Yes')->count() }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="summary-section">
                <div class="section-title">
                    <i class="fas fa-cogs"></i> Operational Summary
                </div>
                <div class="kpi-container">
                    @php
                        $efficiency = $operationDetails->where('activity', 'Efficiency')->first();
                        $production = $operationDetails->where('activity', 'Production')->first();
                    @endphp

                    <div class="kpi-card">
                        <div class="kpi-title">Overall Efficiency</div>
                        <div class="kpi-value">
                            @if ($efficiency)
                                {{ number_format($efficiency->result / 1000, 1) }}%
                            @else
                                N/A
                            @endif
                        </div>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-title">Total Production</div>
                        <div class="kpi-value">
                            @if ($production)
                                {{ number_format($production->result) }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-title">Overtime Achievement</div>
                        <div class="kpi-value">
                            {{ number_format($otAchievements->sum('achievement'), 1) }}%
                        </div>
                    </div>
                </div>
            </div>

            <div class="summary-section">
                <div class="section-title">
                    <i class="fas fa-file-alt"></i> Daily Highlights
                </div>
                <div class="kpi-card">
                    @if ($dailyReport)
                        <div class="kpi-title">Key Incidents & Remarks</div>
                        <div style="margin-top: 15px;">
                            @if ($dailyReport->shipments)
                                <p><i class="fas fa-exclamation-circle color-warning"></i>
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Export Qty</th>
                                            <th>Export Value ($)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dailyReport->shipments as $shipment)
                                            <tr>
                                                <td>{{ $shipment['date'] }}</td>
                                                <td>{{ $shipment['export_qty'] }} pcs</td>
                                                <td>${{ number_format($shipment['export_value'], 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                </p>
                            @endif
                            @if ($dailyReport->remarkable_incident)
                                <p><i class="fas fa-star color-success"></i>
                                    <strong>Positive Note:</strong> {{ $dailyReport->remarkable_incident }}
                                </p>
                            @endif
                            @if ($dailyReport->other_information)
                                <p><i class="fas fa-info-circle color-primary"></i>
                                    <strong>Other:</strong> {{ $dailyReport->other_information }}
                                </p>
                            @endif
                        </div>
                    @else
                        <div class="kpi-title">No highlights recorded today</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // JavaScript for summary dashboard
        document.getElementById('downloadBtn').addEventListener('click', function() {
            // Simplified Excel export for summary data
            const workbook = new ExcelJS.Workbook();
            const worksheet = workbook.addWorksheet('Summary Report');

            // Add summary data
            worksheet.addRow(['Tosrifa Industries - Summary Report', '', '', '']);
            worksheet.addRow(['Date', '{{ $reportDate }}', '', '']);
            worksheet.addRow([]);

            // Attendance summary
            worksheet.addRow(['Attendance Summary']);
            worksheet.addRow(['Total Employees', '{{ $attendanceSummary->sum('onroll') }}']);
            worksheet.addRow(['Present', '{{ $attendanceSummary->sum('present') }}']);
            worksheet.addRow(['Absent', '{{ $attendanceSummary->sum('absent') }}']);
            worksheet.addRow(['On Leave', '{{ $attendanceSummary->sum('leave') }}']);
            worksheet.addRow([]);

            // Operational summary
            worksheet.addRow(['Operational Summary']);
            @if ($efficiency)
                worksheet.addRow(['Efficiency', '{{ number_format($efficiency->result * 100, 1) }}%']);
            @endif
            @if ($production)
                worksheet.addRow(['Production', '{{ number_format($production->result) }}']);
            @endif
            worksheet.addRow(['Shipment', '{{ number_format($otAchievements->sum('achievement'), 1) }}%']);
            worksheet.addRow([]);

            // Generate file
            workbook.xlsx.writeBuffer().then(buffer => {
                const blob = new Blob([buffer], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });
                saveAs(blob, `Tosrifa_Summary_Report_{{ $reportDate }}.xlsx`);
            });
        });
    </script>
</body>

</html>

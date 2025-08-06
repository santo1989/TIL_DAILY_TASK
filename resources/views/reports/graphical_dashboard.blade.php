<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tosrifa Industries - Graphical Dashboard</title>

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
        /* Add chart-specific styles */
        .chart-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 25px;
            margin: 30px 0;
        }

        .chart-container {
            background: white;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 20px;
            height: 400px;
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
            font-size: 16px;
        }

        .mini-chart {
            height: 100px;
            margin-top: 15px;
        }

        .chart-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .metric-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
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
                    <div class="report-title">Graphical Dashboard -
                        {{ \Carbon\Carbon::parse($reportDate)->format('d/m/Y') }}</div>
                </div>
            </div>
            <div class="controls">
                <form method="GET" action="{{ route('dashboard.graphical') }}" class="date-filter">
                    <input type="date" name="report_date" id="reportDate" value="{{ $reportDate }}">
                    <button type="submit" class="btn">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </form>
                <button class="btn btn-download" id="downloadPdfBtn">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </button>
            </div>
        </div>

        <div class="content">
            <div class="chart-grid">
                <!-- Attendance Distribution -->
                <div class="chart-container">
                    <div class="chart-header">
                        <div class="chart-title">Attendance Distribution</div>
                    </div>
                    <canvas id="attendanceChart"></canvas>
                </div>

                <!-- Efficiency by Floor -->
                <div class="chart-container">
                    <div class="chart-header">
                        <div class="chart-title">Efficiency by Floor</div>
                    </div>
                    <canvas id="efficiencyChart"></canvas>
                </div>

                <!-- Absenteeism Analysis -->
                <div class="chart-container">
                    <div class="chart-header">
                        <div class="chart-title">Absenteeism Analysis</div>
                    </div>
                    <canvas id="absenteeismChart"></canvas>

                    <div class="chart-row">
                        <div class="metric-card">
                            <div>Long-term Absences</div>
                            <div style="font-size: 24px; font-weight: bold;">
                                {{ $absentAnalyses->where('total_absent_days', '>', 3)->count() }}
                            </div>
                            <div style="font-size: 12px; color: #777;">>3 days absent</div>
                        </div>
                        <div class="metric-card">
                            <div>Returned Today</div>
                            <div style="font-size: 24px; font-weight: bold; color: #27ae60;">
                                {{ $comeBackReports->count() }}
                            </div>
                            <div style="font-size: 12px; color: #777;">Back to work</div>
                        </div>
                    </div>
                </div>

                <!-- Shipment -->
                <div class="chart-container">
                    <div class="chart-header">
                        <div class="chart-title">Overtime Achievement</div>
                    </div>
                    <canvas id="otChart"></canvas>
                </div>

                <!-- Recruitment Pipeline -->
                <div class="chart-container">
                    <div class="chart-header">
                        <div class="chart-title">Recruitment Pipeline</div>
                    </div>
                    <canvas id="recruitmentChart"></canvas>
                </div>

                <!-- Production Metrics -->
                <div class="chart-container">
                    <div class="chart-header">
                        <div class="chart-title">Production Metrics</div>
                    </div>
                    <canvas id="productionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all charts

            // Attendance Distribution Chart
            new Chart(document.getElementById('attendanceChart'), {
                type: 'pie',
                data: {
                    labels: ['Present', 'Absent', 'Leave', 'ML'],
                    datasets: [{
                        data: [
                            {{ $attendanceSummary->sum('present') }},
                            {{ $attendanceSummary->sum('absent') }},
                            {{ $attendanceSummary->sum('leave') }},
                            {{ $attendanceSummary->sum('ml') }}
                        ],
                        backgroundColor: [
                            '#27ae60',
                            '#e74c3c',
                            '#f39c12',
                            '#3498db'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Efficiency by Floor Chart
            const efficiencyData = {!! json_encode($operationDetails->where('activity', 'Efficiency')->first()) !!};
            new Chart(document.getElementById('efficiencyChart'), {
                type: 'bar',
                data: {
                    labels: ['1st', '2nd', '3rd', '4th', '5th'],
                    datasets: [{
                        label: 'Efficiency %',
                        data: [
                            efficiencyData ? efficiencyData.floor_1 * 100 : 0,
                            efficiencyData ? efficiencyData.floor_2 * 100 : 0,
                            efficiencyData ? efficiencyData.floor_3 * 100 : 0,
                            efficiencyData ? efficiencyData.floor_4 * 100 : 0,
                            efficiencyData ? efficiencyData.floor_5 * 100 : 0
                        ],
                        backgroundColor: '#2c6eb5'
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

            // Absenteeism Analysis Chart
            const absentDaysData = @json($absentAnalyses->groupBy('total_absent_days')->map->count());
            new Chart(document.getElementById('absenteeismChart'), {
                type: 'line',
                data: {
                    labels: Object.keys(absentDaysData).sort(),
                    datasets: [{
                        label: 'Number of Employees',
                        data: Object.values(absentDaysData),
                        borderColor: '#e74c3c',
                        backgroundColor: 'rgba(231, 76, 60, 0.1)',
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Shipment Chart
            new Chart(document.getElementById('otChart'), {
                type: 'doughnut',
                data: {
                    labels: ['2 Hours OT', 'Above 2 Hours OT'],
                    datasets: [{
                        data: [
                            {{ $otAchievements->sum('two_hours_ot_persons') }},
                            {{ $otAchievements->sum('above_two_hours_ot_persons') }}
                        ],
                        backgroundColor: ['#3498db', '#2c3e50']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Recruitment Pipeline Chart
            const recruitmentData = {
                interviewed: {{ $recruitmentSummary->count() }},
                selected: {{ $recruitmentSummary->where('selected', 'Yes')->count() }},
                joined: {{ $recruitmentSummary->where('probable_date_of_joining', \Carbon\Carbon::parse($reportDate)->format('Y-m-d'))->count() }}
            };

            new Chart(document.getElementById('recruitmentChart'), {
                type: 'bar',
                data: {
                    labels: ['Interviewed', 'Selected', 'Joined'],
                    datasets: [{
                        label: 'Recruitment Pipeline',
                        data: [
                            recruitmentData.interviewed,
                            recruitmentData.selected,
                            recruitmentData.joined
                        ],
                        backgroundColor: '#9b59b6'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Production Metrics Chart
            const productionData = {!! json_encode($operationDetails->where('activity', 'Production')->first()) !!};
            new Chart(document.getElementById('productionChart'), {
                type: 'radar',
                data: {
                    labels: ['1st', '2nd', '3rd', '4th', '5th'],
                    datasets: [{
                        label: 'Production Output',
                        data: [
                            productionData ? productionData.floor_1 : 0,
                            productionData ? productionData.floor_2 : 0,
                            productionData ? productionData.floor_3 : 0,
                            productionData ? productionData.floor_4 : 0,
                            productionData ? productionData.floor_5 : 0
                        ],
                        backgroundColor: 'rgba(46, 204, 113, 0.2)',
                        borderColor: '#2ecc71',
                        pointBackgroundColor: '#2ecc71'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // PDF Export
            document.getElementById('downloadPdfBtn').addEventListener('click', function() {
                html2canvas(document.querySelector('.dashboard')).then(canvas => {
                    const imgData = canvas.toDataURL('image/png');
                    const pdf = new jsPDF('landscape');
                    const imgProps = pdf.getImageProperties(imgData);
                    const pdfWidth = pdf.internal.pageSize.getWidth();
                    const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;

                    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                    pdf.save(`Tosrifa_Graphical_Report_{{ $reportDate }}.pdf`);
                });
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factory Operations Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --success: #27ae60;
            --warning: #f39c12;
            --danger: #e74c3c;
            --light: #ecf0f1;
            --dark: #34495e;
            --gray: #95a5a6;
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
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            border-bottom: 2px solid var(--primary);
            margin-bottom: 25px;
        }

        .header h1 {
            color: var(--primary);
            font-size: 28px;
        }

        .report-date {
            background: var(--primary);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 20px;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary);
        }

        .card-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(52, 152, 219, 0.1);
            color: var(--secondary);
        }

        .kpi-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .kpi-card {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
        }

        .kpi-value {
            font-size: 24px;
            font-weight: 700;
            margin: 5px 0;
        }

        .kpi-label {
            font-size: 13px;
            color: var(--gray);
            text-transform: uppercase;
        }

        .chart-container {
            height: 250px;
            position: relative;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f8f9fa;
            color: var(--dark);
            font-weight: 600;
        }

        tr:hover {
            background-color: #f5f7fa;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-present {
            background: rgba(39, 174, 96, 0.15);
            color: var(--success);
        }

        .badge-absent {
            background: rgba(231, 76, 60, 0.15);
            color: var(--danger);
        }

        .badge-leave {
            background: rgba(52, 152, 219, 0.15);
            color: var(--secondary);
        }

        .badge-late {
            background: rgba(243, 156, 18, 0.15);
            color: var(--warning);
        }

        .progress-container {
            margin: 15px 0;
        }

        .progress-bar {
            height: 8px;
            background: #e0e0e0;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 4px;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
            font-size: 12px;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: var(--gray);
            font-size: 14px;
            border-top: 1px solid #eee;
            margin-top: 20px;
        }

        .color-primary {
            color: var(--primary);
        }

        .color-success {
            color: var(--success);
        }

        .color-warning {
            color: var(--warning);
        }

        .color-danger {
            color: var(--danger);
        }

        .color-secondary {
            color: var(--secondary);
        }

        @media (max-width: 768px) {
            .grid-container {
                grid-template-columns: 1fr;
            }

            .kpi-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    @php
        // Set default empty values for all variables
        $attendanceSummary = $attendanceSummary ?? collect();
        $otAchievements = $otAchievements ?? collect();
        $recruitmentSummary = $recruitmentSummary ?? collect();
        $operationDetails = $operationDetails ?? collect();
        $attendanceReports = $attendanceReports ?? collect();
        $comeBackReports = $comeBackReports ?? collect();
        $absentAnalyses = $absentAnalyses ?? collect();
        $dailyReport = $dailyReport ?? null;
    @endphp
    <div class="dashboard">
        <div class="header">
            <h1><i class="fas fa-industry"></i> Factory Operations Dashboard</h1>
            <div class="report-date">
                {{-- <i class="fas fa-calendar-alt"></i> <span id="currentDate">{{ now()->format('F d, Y') }}</span> --}}
                <!--date filter form -->
                <form method="GET" action="{{ route('todayReport') }}" style="display: inline;">
                    <input type="date" name="date" value="{{ request('date', now()->format('Y-m-d')) }}" required
                        id="currentDate">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>
                {{-- <span id="currentDate">{{ now()->format('F d, Y') }}</span> --}}

            </div>
        </div>

        <div class="grid-container">
            <!-- Attendance Summary -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Attendance Summary</div>
                    <div class="card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="kpi-container">
                    <div class="kpi-card">
                        <div class="kpi-label">On Roll</div>
                        <div class="kpi-value color-primary">{{ $attendanceSummary->sum('onroll') }}</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kpi-label">Present</div>
                        <div class="kpi-value color-success">{{ $attendanceSummary->sum('present') }}</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kpi-label">Absent</div>
                        <div class="kpi-value color-danger">{{ $attendanceSummary->sum('absent') }}</div>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>

            <!-- Shipments -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Shipments</div>
                    <div class="card-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="kpi-container">
                    <div class="kpi-card">
                        <div class="kpi-label">2H OT Persons</div>
                        <div class="kpi-value color-secondary">{{ $otAchievements->sum('two_hours_ot_persons') }}</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kpi-label">Above 2H OT</div>
                        <div class="kpi-value color-warning">{{ $otAchievements->sum('above_two_hours_ot_persons') }}
                        </div>
                    </div>
                    <div class="kpi-card">
                        <div class="kpi-label">Achievement</div>
                        <div class="kpi-value color-success">
                            {{ number_format($otAchievements->avg('achievement'), 1) }}%</div>
                    </div>
                </div>
                <div class="progress-container">
                    <div class="progress-label">
                        <span>OT Goal Progress</span>
                        <span>{{ number_format($otAchievements->avg('achievement'), 1) }}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill"
                            style="width: {{ $otAchievements->avg('achievement') }}%; background: var(--success);">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recruitment Summary -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Recruitment Summary</div>
                    <div class="card-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                </div>
                <div class="kpi-container">
                    <div class="kpi-card">
                        <div class="kpi-label">Candidates</div>
                        <div class="kpi-value color-primary">{{ $recruitmentSummary->count() }}</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kpi-label">Selected</div>
                        <div class="kpi-value color-success">
                            {{ $recruitmentSummary->whereNotNull('selected')->count() }}</div>
                    </div>
                    <div class="kpi-card">
                        <div class="kpi-label">Joined</div>
                        <div class="kpi-value color-secondary">
                            {{ $recruitmentSummary->whereNotNull('probable_date_of_joining')->count() }}</div>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="recruitmentChart"></canvas>
                </div>
            </div>

            <!-- Operation Details -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Operation Efficiency</div>
                    <div class="card-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Activity</th>
                                <th>F1</th>
                                <th>F2</th>
                                <th>F3</th>
                                <th>F4</th>
                                <th>Result</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($operationDetails as $operation)
                                <tr>
                                    <td>{{ $operation->activity }}</td>
                                    <td>{{ $operation->floor_1 }}%</td>
                                    <td>{{ $operation->floor_2 }}%</td>
                                    <td>{{ $operation->floor_3 }}%</td>
                                    <td>{{ $operation->floor_4 }}%</td>
                                    <td class="color-success">{{ $operation->result }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Attendance Details -->
            <div class="card full-width">
                <div class="card-header">
                    <div class="card-title">Attendance Details</div>
                    <div class="card-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
                <div class="grid-container" style="grid-template-columns: 1fr 1fr;">
                    <div>
                        <h3 style="margin-bottom: 15px; color: var(--primary);">Attendance Analysis</h3>
                        <div class="chart-container">
                            <canvas id="attendanceAnalysisChart"></canvas>
                        </div>
                    </div>
                    <div>
                        <h3 style="margin-bottom: 15px; color: var(--primary);">Recent Issues</h3>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Count</th>
                                        <th>Floor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="status-badge badge-late">Late Comers</span></td>
                                        <td>{{ $attendanceReports->where('type', 'late_comer')->count() }}</td>
                                        <td>{{ $attendanceReports->where('type', 'late_comer')->pluck('floor')->unique()->implode(', ') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="status-badge badge-absent">To Be Absent</span></td>
                                        <td>{{ $attendanceReports->where('type', 'to_be_absent')->count() }}</td>
                                        <td>{{ $attendanceReports->where('type', 'to_be_absent')->pluck('floor')->unique()->implode(', ') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="status-badge badge-leave">On Leave</span></td>
                                        <td>{{ $attendanceReports->where('type', 'on_leave')->count() }}</td>
                                        <td>{{ $attendanceReports->where('type', 'on_leave')->pluck('floor')->unique()->implode(', ') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="status-badge badge-absent">ML Absence</span></td>
                                        <td>{{ $attendanceReports->where('type', 'ml_absence')->count() }}</td>
                                        <td>{{ $attendanceReports->where('type', 'ml_absence')->pluck('floor')->unique()->implode(', ') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employee Details -->
            <div class="card full-width">
                <div class="card-header">
                    <div class="card-title">Employee Status Details</div>
                    <div class="card-icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                </div>
                <div class="grid-container" style="grid-template-columns: 1fr 1fr;">
                    <div>
                        <h3 style="margin-bottom: 15px; color: var(--primary);">Recent Comebacks</h3>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Employee ID</th>
                                        <th>Name</th>
                                        <th>Floor</th>
                                        <th>Absent Days</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($comeBackReports as $report)
                                        <tr>
                                            <td>{{ $report->employee_id }}</td>
                                            <td>{{ $report->name }}</td>
                                            <td>{{ $report->floor }}</td>
                                            <td>{{ $report->absent_days }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div>
                        <h3 style="margin-bottom: 15px; color: var(--primary);">Absent Analysis</h3>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Employee ID</th>
                                        <th>Name</th>
                                        <th>Last Present</th>
                                        <th>Absent Days</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($absentAnalyses as $analysis)
                                        <tr>
                                            <td>{{ $analysis->employee_id }}</td>
                                            <td>{{ $analysis->name }}</td>
                                            <td>{{ $analysis->last_p_date }}</td>
                                            <td>{{ $analysis->total_absent_days }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daily Reports -->
            <div class="card full-width">
                <div class="card-header">
                    <div class="card-title">Daily Reports & Remarks</div>
                    <div class="card-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
                <div class="grid-container" style="grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <h3 style="margin-bottom: 15px; color: var(--primary);">Shipments</h3>
                        <ul style="list-style-type: none; padding-left: 0;">
                            @if ($dailyReport && $dailyReport->shipments)
                                @foreach (json_decode($dailyReport->shipments) as $shipment)
                                    <li style="padding: 8px 0; border-bottom: 1px solid #eee;">
                                        <i class="fas fa-shipping-fast color-secondary"></i>
                                        <strong>{{ $shipment->order }}</strong> - {{ $shipment->units }} to
                                        {{ $shipment->destination }}
                                    </li>
                                @endforeach
                            @else
                                <li style="padding: 8px 0;">No shipments today</li>
                            @endif
                        </ul>
                    </div>
                    <div>
                        <h3 style="margin-bottom: 15px; color: var(--primary);">Remarks</h3>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                            @if ($dailyReport)
                                @if ($dailyReport->improvement_area)
                                    <p><i class="fas fa-exclamation-circle color-warning"></i> <strong>Improvement
                                            Area:</strong> {{ $dailyReport->improvement_area }}</p>
                                @endif
                                @if ($dailyReport->remarkable_incident)
                                    <p><i class="fas fa-star color-success"></i> <strong>Positive Note:</strong>
                                        {{ $dailyReport->remarkable_incident }}</p>
                                @endif
                                @if ($dailyReport->other_information)
                                    <p><i class="fas fa-info-circle color-primary"></i> <strong>Other:</strong>
                                        {{ $dailyReport->other_information }}</p>
                                @endif
                            @else
                                <p>No remarks available</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Generated on <span id="currentDateTime">{{ now()->format('F d, Y H:i') }}</span> | Factory Operations
                Dashboard v1.0</p>
        </div>
    </div>

    <script>
        // Set current date and time
        const now = new Date();
        document.getElementById('currentDate').textContent = now.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        document.getElementById('currentDateTime').textContent = now.toLocaleString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

        // Initialize charts
        document.addEventListener('DOMContentLoaded', function() {
            // Attendance Chart
            const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
            new Chart(attendanceCtx, {
                type: 'bar',
                data: {
                    labels: ['Floor 1', 'Floor 2', 'Floor 3', 'Floor 4'],
                    datasets: [{
                            label: 'Present',
                            data: [
                                {{ $attendanceSummary->where('floor', 'F1')->first()->present ?? 0 }},
                                {{ $attendanceSummary->where('floor', 'F2')->first()->present ?? 0 }},
                                {{ $attendanceSummary->where('floor', 'F3')->first()->present ?? 0 }},
                                {{ $attendanceSummary->where('floor', 'F4')->first()->present ?? 0 }}
                            ],
                            backgroundColor: '#27ae60',
                        },
                        {
                            label: 'Absent',
                            data: [
                                {{ $attendanceSummary->where('floor', 'F1')->first()->absent ?? 0 }},
                                {{ $attendanceSummary->where('floor', 'F2')->first()->absent ?? 0 }},
                                {{ $attendanceSummary->where('floor', 'F3')->first()->absent ?? 0 }},
                                {{ $attendanceSummary->where('floor', 'F4')->first()->absent ?? 0 }}
                            ],
                            backgroundColor: '#e74c3c',
                        },
                        {
                            label: 'Leave',
                            data: [
                                {{ $attendanceSummary->where('floor', 'F1')->first()->leave ?? 0 }},
                                {{ $attendanceSummary->where('floor', 'F2')->first()->leave ?? 0 }},
                                {{ $attendanceSummary->where('floor', 'F3')->first()->leave ?? 0 }},
                                {{ $attendanceSummary->where('floor', 'F4')->first()->leave ?? 0 }}
                            ],
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

            // Recruitment Chart
            const recruitmentCtx = document.getElementById('recruitmentChart').getContext('2d');
            new Chart(recruitmentCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Selected', 'Rejected', 'Pending'],
                    datasets: [{
                        data: [
                            {{ $recruitmentSummary->whereNotNull('selected')->count() }},
                            {{ $recruitmentSummary->whereNull('selected')->count() }},
                            {{ $recruitmentSummary->where('selected', 'pending')->count() }}
                        ],
                        backgroundColor: [
                            '#27ae60',
                            '#e74c3c',
                            '#f39c12'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Attendance Analysis Chart
            const analysisCtx = document.getElementById('attendanceAnalysisChart').getContext('2d');
            new Chart(analysisCtx, {
                type: 'radar',
                data: {
                    labels: ['Punctuality', 'Absenteeism', 'Leave Balance', 'OT Compliance',
                        'Shift Coverage'
                    ],
                    datasets: [{
                            label: 'Current Month',
                            data: [
                                @php
                                    // Safe calculation function
                                    function calculatePercentage($numerator, $denominator)
                                    {
                                        return $denominator != 0 ? ($numerator / $denominator) * 100 : 0;
                                    }

                                    $onrollAvg = $attendanceSummary->avg('onroll') ?? 1;
                                    $presentAvg = $attendanceSummary->avg('present') ?? 0;
                                    $absentAvg = $attendanceSummary->avg('absent') ?? 0;
                                    $leaveAvg = $attendanceSummary->avg('leave') ?? 0;
                                    $otAchievementAvg = $otAchievements->avg('achievement') ?? 0;
                                @endphp

                                {{ calculatePercentage($presentAvg, $onrollAvg) }},
                                {{ calculatePercentage($absentAvg, $onrollAvg) }},
                                {{ calculatePercentage($leaveAvg, $onrollAvg) }},
                                {{ $otAchievementAvg }},
                                85 // Placeholder value
                            ],
                            fill: true,
                            backgroundColor: 'rgba(52, 152, 219, 0.2)',
                            borderColor: 'rgba(52, 152, 219, 1)',
                            pointBackgroundColor: 'rgba(52, 152, 219, 1)',
                            pointBorderColor: '#fff',
                        },
                        {
                            label: 'Previous Month',
                            data: [80, 85, 88, 82, 75],
                            fill: true,
                            backgroundColor: 'rgba(46, 204, 113, 0.2)',
                            borderColor: 'rgba(46, 204, 113, 1)',
                            pointBackgroundColor: 'rgba(46, 204, 113, 1)',
                            pointBorderColor: '#fff',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            angleLines: {
                                display: true
                            },
                            suggestedMin: 50,
                            suggestedMax: 100
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>

<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Daily Reports
    </x-slot>

    <x-backend.layouts.elements.errors />
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12 text-center">
                <h2> Shipment Status, DHU Report, Others</h2>
            </div>
        </div>

    </div>
    
        <div class="card">
            <div class="card-header">

                <div class="row">
                    <div class="col-md-12 d-flex justify-content-between align-items-center flex-nowrap">
                        <!-- Left Group -->
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('home') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <a href="{{ route('daily-reports.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Create Report
                            </a>
                            <form method="GET" class="d-flex flex-nowrap gap-2">
                                <input type="date" name="report_date" class="form-control form-control-sm"
                                    value="{{ request('report_date') }}" style="width: 150px;">
    
                                <button class="btn btn-primary btn-sm">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                            </form>
                        </div>
    
                        <!-- Right Group -->
                        <div class="d-flex align-items-center gap-2">
                            <!--reset filter button-->
                            <a href="{{ route('daily-reports.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-sync"></i> Reset Filter
                            </a>
                            <a href="{{ route('daily-reports.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create Report
                            </a>
                        </div>
                    </div>
                </div>
    
            </div>
    
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
    
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Incident</th>
                                <th>Improvement Area</th>
                                <th>Other Information</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                            <tr>
                                <td>{{ $report->report_date->format('Y-m-d') }}</td>
                                <td>{{ Str::limit($report->remarkable_incident, 50) }}</td>
                                <td>{{ Str::limit($report->improvement_area, 50) }}</td>
                                <td>{{ Str::limit($report->other_information, 50) }}</td>
                                <td>
                                    <a href="{{ route('daily-reports.edit', $report) }}" 
                                       class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('daily-reports.destroy', $report) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $reports->links() }}
            </div>
        </div>
    </x-backend.layouts.master>
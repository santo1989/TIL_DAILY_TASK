<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Attendance Reports (Lunch Out, Late Comer, To be Absent, On Leave)
    </x-slot>

    <x-backend.layouts.elements.errors />
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12 text-center">
                <h2> Attendance Reports (Lunch Out, Late Comer, To be Absent, On Leave)</h2>
            </div>
        </div>

    </div>
    <div class="card">
        <div class="card-header">
            
            <div class="row">
                
                <div class="col-md-12 justify-content-between align-items-center">

                    <!--back button-->
                    <a href="{{ route('home') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    <!--add button-->
                    <a href="" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Data
                    </a>
                    <!--floor filter-->
                    <form action="{{ route('attendance-reports.index') }}" method="GET" class="form-inline" style="margin-left: 20px; display: inline-block;">
                        <select name="floor" class="form-control mr-2" onchange="this.form.submit()">
                            <option value="">Select Floor</option>
                            <option value="1st Floor" {{ request('floor') == '1st Floor' ? 'selected' : '' }}>1st Floor</option>
                            <option value="3rd Floor" {{ request('floor') == '3rd Floor' ? 'selected' : '' }}>3rd Floor</option>
                            <option value="4th Floor" {{ request('floor') == '4th Floor' ? 'selected' : '' }}>4th Floor</option>
                            <option value="5th Floor" {{ request('floor') == '5th Floor' ? 'selected' : '' }}>5th Floor</option>
                        </select>
                        <input type="text" name="employee_id" class="form-control mr-2" placeholder="Search by Employee ID or Name" value="{{ request('employee_id') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('attendance-reports.index') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-sync"></i> Reset
                        </a>
                    </form>

                {{-- </div>
                <div class="col-md-3"> --}}
                    <!--modal trigger button for download and upload excel file -->
                    <a type="button" class="btn btn-success"
                        href="{{ route('attendance-reports.download.template') }}">
                        <i class="fas fa-download"></i> Download Template </a>
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#uploadModal">
                        <i class="fas fa-upload"></i> Upload Excel
                    </button>
                </div>

            </div>
           
        </div>
        
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Sl</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Employee</th>
                            <th>Details</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <tr>
                            <td>{{ $loop->iteration + ($reports->currentPage() - 1) * $reports->perPage() }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $report->type)) }}</td>
                    <td>{{ $report->report_date->format('Y-m-d') }}</td>
                    <td>
                        {{ $report->name }}<br>
                        <small>{{ $report->designation }}</small>
                    </td>
                    <td>
                        @if($report->type === 'late_comer')
                            In Time: {{ $report->in_time }}<br>
                           
                        @else
                            Reason: {{ $report->reason }}
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('attendance-reports.edit', $report) }}" class="btn btn-sm btn-warning">
                            Edit
                        </a>
                        <form action="{{ route('attendance-reports.destroy', $report) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center">No records found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center">
                {{ $reports->links() }}
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload Operator Absent Analysis</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('attendance-reports.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="excel_file">Excel File</label>
                            <input type="file" class="form-control-file" id="excel_file" name="excel_file" required>
                        </div>
                        <div class="form-group">
                            <label for="report_date">Report Date</label>
                            <input type="date" class="form-control" id="report_date" name="report_date" 
                                   value="{{ now()->format('Y-m-d') }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Upload
                        </button>
                    </form>
                    <h1>type are = Lunch Out,Late Comer,To be Absent,On Leave</h1>
                </div>
            </div>
        </div>
    </div>
</x-backend.layouts.master>
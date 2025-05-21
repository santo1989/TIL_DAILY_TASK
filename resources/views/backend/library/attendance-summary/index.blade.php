<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Attendance Summary Management
    </x-slot>

    {{-- <x-slot name='breadCrumb'>
        <x-backend.layouts.elements.breadcrumb>
            <x-slot name="pageHeader"> buyer </x-slot>
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('buyers.index') }}">Attendance Summary Management</a></li>
            <li class="breadcrumb-item active">Attendance Summary Management</li>
        </x-backend.layouts.elements.breadcrumb>
    </x-slot> --}}


    <x-backend.layouts.elements.errors />
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2>Attendance Summary Management</h2>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif



        <div class="card">
            <div class="card-header">
                <div class="row">
                    {{-- <h5 class="text-center" >Existing Attendance Records</h5> --}}
                    <div class="col-md-6">

                        <!--back button-->
                        <a href="{{ route('home') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <!--add button-->
                        <a href="" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Attendance Record
                        </a>

                    </div>
                    <div class="col-md-6">
                        <!--modal trigger button for download and upload excel file -->
                        <a type="button" class="btn btn-success"
                            href="{{ route('attendance.summary.download.template') }}">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#uploadModal">
                            <i class="fas fa-upload"></i> Upload Excel
                        </button>
                    </div>

                </div>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Report Date</th>
                                <th>Floor</th>
                                <th>Onroll</th>
                                <th>Present</th>
                                <th>Absent</th>
                                <th>Leave</th>
                                <th>M/L</th>
                                <th>Remarks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Today Total</td>
                                <td>-</td>
                                <td>{{ number_format($todayTotal['onroll']) ?? '-' }}</td>
                                <td>{{ number_format($todayTotal['present']) ?? '-' }}</td>
                                <td>{{ number_format($todayTotal['absent']) ?? '-' }}</td>
                                <td>{{ number_format($todayTotal['leave']) ?? '-' }}</td>
                                <td>{{ number_format($todayTotal['ml']) ?? '-' }}</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            @forelse($attendanceData as $record)
                                <tr>
                                    <td>{{ $record->report_date->format('Y-m-d') }}</td>
                                    <td>{{ $record->floor }}</td>
                                    <td>{{ number_format($record->onroll) }}</td>
                                    <td>{{ number_format($record->present) }}</td>
                                    <td>{{ number_format($record->absent) }}</td>
                                    <td>{{ number_format($record->leave) }}</td>
                                    <td>{{ number_format($record->ml) }}</td>
                                    <td>{{ $record->remarks ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('attendance.summary.edit', $record) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('attendance.summary.destroy', $record) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this record?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No attendance records found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $attendanceData->links() }}
            </div>
        </div>

        <!-- Modal for Upload Excel -->
        <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">Upload Attendance Excel</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>Upload Excel File</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('attendance.summary.upload') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="excel_file">Excel File</label>
                                                <input type="file" class="form-control-file" id="excel_file"
                                                    name="excel_file" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="report_date">Report Date (optional)</label>
                                                <input type="date" class="form-control" id="report_date"
                                                    name="report_date" value="{{ now()->format('Y-m-d') }}">
                                                <small class="form-text text-muted">If not provided, today's date will
                                                    be used.</small>

                                                @error('report_date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-primary mt-4">
                                                <i class="fas fa-upload"></i> Upload
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <p>Ensure the Excel file is in the correct format. The first row should contain the headers:
                            Report Date, Floor, Onroll, Present, Absent, Leave, M/L, Remarks.
                        </p>
                        <p>For any issues, please contact the admin.</p>
                        <div class="alert alert-info">
                            <strong>Note:</strong> The system will automatically check for duplicate entries based on
                            the report date and floor.
                            If duplicates are found, the system will skip those entries and only insert new records.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>





    </div>
</x-backend.layouts.master>

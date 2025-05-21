<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Come Back Reports Management
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
                <h2> Come Back Reports Management</h2>
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
                            <i class="fas fa-plus"></i> Add  Come Back Reports
                        </a>

                    </div>
                    <div class="col-md-6">
                        <!--modal trigger button for download and upload excel file -->
                        <a type="button" class="btn btn-success"
                            href="{{ route('comeback.reports.download.template') }}">
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
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Floor</th>
                                <th>Absent Days</th>
                                <th>Reason</th>
                                <th>Councilor</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $report)
                            <tr>
                                <td>{{ $report->report_date }}</td>
                                <td>{{ $report->employee_id }}</td>
                                <td>{{ $report->name }}</td>
                                <td>{{ $report->designation }}</td>
                                <td>{{ $report->floor }}</td>
                                <td>{{ $report->absent_days }}</td>
                                <td>{{ $report->reason }}</td>
                                <td>{{ $report->councilor_name }}</td>
                                <td>
                                    <a href="{{ route('comeback.reports.edit', $report->id) }}" 
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('comeback.reports.destroy', $report->id) }}" 
                                          method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">No records found</td>
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

        <!-- Modal for Upload Excel -->
        <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">Upload comeback reports Excel</h5>
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
                                <form action="{{ route('comeback.reports.upload') }}" method="POST"
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
                        <p>
                            Please ensure that the Excel file is in the correct format. The first row should contain
                            the headers:
                        </p>
                        <p>For any issues, please contact the admin.</p>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>





    </div>
</x-backend.layouts.master>

<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Operation Details
    </x-slot>

    <x-backend.layouts.elements.errors />
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12 text-center">
                <h2>Operation Details Report</h2>
            </div>
        </div>
        <!--success message -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <!--error message -->
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <div class="card">
        <div class="card-header">
            <div class="col-md-12 d-flex justify-content-between align-items-center flex-nowrap">
                <!-- Left Group -->
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('home') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    <a href="" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add
                    </a>
                    <form method="GET" class="d-flex flex-nowrap">
                        <input type="date" name="report_date" class="form-control form-control-sm mr-2" 
                            value="{{ request('report_date') }}" style="width: 150px;">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </form>
                </div>
        
                <!-- Right Group -->
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('operation-details.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-sync"></i> Reset
                    </a>
                    <a href="{{ route('operation-details.download.template') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-download"></i> Template
                    </a>
                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#uploadModal">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Date</th>
                            <th>Activity</th>
                            <th>1st Floor</th>
                            <th>2nd Floor</th>
                            <th>3rd Floor</th>
                            <th>4th Floor</th>
                            <th>5th Floor</th>
                            <th>Total/Average</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($details as $detail)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @php
                                        $date = \Carbon\Carbon::parse($detail->report_date);
                                        $formattedDate = $date->format('d-m-Y');
                                    @endphp
                                    {{ $formattedDate }}
                                </td>
                                <td>{{ $detail->activity }}</td>
                                <td>{{ $detail->floor_1 }}</td>
                                <td>{{ $detail->floor_2 }}</td>
                                <td>{{ $detail->floor_3 }}</td>
                                <td>{{ $detail->floor_4 }}</td>
                                <td>{{ $detail->floor_5 }}</td>
                                <td>{{ $detail->result }}</td>
                                <td>{{ $detail->remarks }}</td>
                                <td>
                                    <a href="{{ route('operation-details.edit', $detail) }}"
                                        class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('operation-details.destroy', $detail) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $details->links() }}
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Operation Details</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('operation-details.upload') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Excel File</label>
                            <input type="file" name="excel_file" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Report Date</label>
                            <input type="date" name="report_date" class="form-control" required
                                value="{{ old('report_date', now()->format('Y-m-d')) }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-backend.layouts.master>

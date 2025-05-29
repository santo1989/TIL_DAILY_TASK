<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Floor Timings
    </x-slot>

    <x-backend.layouts.elements.errors />
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12 text-center">
                <h2> Floor Timings</h2>
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
                        <a href="" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Data
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
                        <a href="{{ route('floor-timings.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-sync"></i> Reset Filter
                        </a>
                        <!-- Download and Upload buttons -->
                        <a href="{{ route('floor-timings.download.template') }}" class="btn btn-success">
                            <i class="fas fa-download"></i> Template
                        </a>
                        <button class="btn btn-info" data-toggle="modal" data-target="#uploadModal">
                            <i class="fas fa-upload"></i> Upload
                        </button>
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
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Date</th>
                            <th>Floor</th>
                            <th>Start Time</th>
                            <th>Start Responsible</th>
                            <th>Close Time</th>
                            <th>Close Responsible</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($timings as $timing)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $timing->report_date->format('Y-m-d') }}</td>
                                <td>{{ $timing->floor }}</td>
                                <td>{{ $timing->starting_time->format('H:i') }}</td>
                                <td>
                                    @foreach ($timing->starting_responsible as $person)
                                        {{ $person['name'] }} ({{ $person['role'] }})@if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ $timing->closing_time->format('H:i') }}</td>
                                <td>
                                    @foreach ($timing->closing_responsible as $person)
                                        {{ $person['name'] }} ({{ $person['role'] }})@if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    <a href="{{ route('floor-timings.edit', $timing) }}"
                                        class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('floor-timings.destroy', $timing) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $timings->links() }}
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Floor Timings</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('floor-timings.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Excel File</label>
                            <input type="file" name="excel_file" class="form-control-file" required>
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

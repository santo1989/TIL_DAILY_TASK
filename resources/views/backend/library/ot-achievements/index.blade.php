<x-backend.layouts.master>
    <x-slot name="pageTitle">
        OT Achievements
    </x-slot>

    <x-backend.layouts.elements.errors />
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12 text-center">
                <h2> OT Achievements</h2>
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
                            <select name="floor" class="form-control form-control-sm" style="width: 120px;">
                                <option value="">All Floors</option>
                                @foreach (['1st Floor', '2nd Floor', '3rd Floor', '4th Floor', '5th Floor'] as $floor)
                                    <option value="{{ $floor }}" {{ request('floor') == $floor ? 'selected' : '' }}>
                                        {{ $floor }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary btn-sm">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </form>
                    </div>
            
                    <!-- Right Group -->
                    <div class="d-flex align-items-center gap-2">
                        <!--reset filter button-->
                        <a href="{{ route('ot-achievements.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-sync"></i> Reset Filter
                        </a>
                        <!-- Download and Upload buttons -->
                        <a href="{{ route('ot-achievements.download.template') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-download"></i> Template
                        </a>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#uploadModal">
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
                            <th>Report Date</th>
                            <th>Floor</th>
                            <th>2H OT Persons</th>
                            <th>Above 2H OT Persons</th>
                            <th>Achievement</th>
                            <th>Remarks</th>
                            
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($achievements as $record)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $record->report_date->format('Y-m-d') }}</td>
                                <td>{{ $record->floor }}</td>
                                <td>{{ $record->two_hours_ot_persons }}</td>
                                <td>{{ $record->above_two_hours_ot_persons }}</td>
                                <td>{{ number_format($record->achievement, 2) }}</td>
                                <td>{{ $record->remarks }}</td>
                                
                                <td>
                                    <a href="{{ route('ot-achievements.edit', $record) }}"
                                        class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('ot-achievements.destroy', $record) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $achievements->links() }}
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload OT Achievements</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('ot-achievements.upload') }}" method="POST" enctype="multipart/form-data">
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

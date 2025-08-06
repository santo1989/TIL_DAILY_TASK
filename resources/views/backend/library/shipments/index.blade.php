<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Shipments
    </x-slot>

    <x-backend.layouts.elements.errors />
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12 text-center">
                <h2> Shipments</h2>
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
                        <a href="{{ route('shipments.create') }}" class="btn btn-primary btn-sm">
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
                        <a href="{{ route('shipments.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-sync"></i> Reset Filter
                        </a>
                        <!-- Download and Upload buttons -->
                        {{-- <a href="{{ route('shipments.download.template') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-download"></i> Template
                        </a>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#uploadModal">
                            <i class="fas fa-upload"></i> Upload
                        </button> --}}
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
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Report Date</th>
                            <th>Date</th>
                            <th>Export Qty</th>
                            <th>Export Value ($)</th>
                            <th>Remarks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shipments as $shipment)
                            <tr>
                                <td>{{ Carbon\Carbon::parse($shipment->report_date)->format('Y-m-d') }}</td>
                                <td>{{ Carbon\Carbon::parse($shipment->shipment_date)->format('Y-m-d') }}</td>
                                <td>{{ $shipment->export_qty }} pcs</td>
                                <td>${{ number_format($shipment->export_value, 2) }}</td>
                                <td>{{ $shipment->remarks }}</td>
                                <td>
                                    <a href="{{ route('shipments.edit', $shipment->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('shipments.destroy', $shipment->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this shipment?');">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $shipments->links() }}
        </div>
    </div>

    <!-- Upload Modal -->
    {{-- <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Shipments</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('shipments.upload') }}" method="POST" enctype="multipart/form-data">
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
    </div> --}}
</x-backend.layouts.master>

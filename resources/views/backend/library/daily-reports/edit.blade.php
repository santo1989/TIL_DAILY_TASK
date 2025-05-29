<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Edit Daily Report
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h4>Edit Daily Report - {{ $report->report_date->format('Y-m-d') }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('daily-reports.update', $report) }}" method="POST">
                @csrf @method('PUT')
                
                <div class="form-group">
                    <label>Report Date</label>
                    <input type="date" name="report_date" class="form-control" 
                           value="{{ $report->report_date->format('Y-m-d') }}" required>
                </div>

                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h5>Shipment Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-4 font-weight-bold">Date</div>
                            <div class="col-md-4 font-weight-bold">Export Qty</div>
                            <div class="col-md-4 font-weight-bold">Export Value ($)</div>
                        </div>
                        @foreach($report->shipments ?? [] as $index => $shipment)
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <input type="date" name="shipment_date[]" class="form-control"
                                       value="{{ $shipment['date'] }}">
                            </div>
                            <div class="col-md-4">
                                <input type="number" name="export_qty[]" class="form-control"
                                       value="{{ $shipment['export_qty'] }}">
                            </div>
                            <div class="col-md-4">
                                <input type="number" step="0.01" name="export_value[]" class="form-control"
                                       value="{{ $shipment['export_value'] }}">
                            </div>
                        </div>
                        @endforeach
                        <button type="button" class="btn btn-sm btn-info" id="add-shipment">
                            <i class="fas fa-plus"></i> Add Shipment
                        </button>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h5>DHU Report</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-6 font-weight-bold">Floor</div>
                            <div class="col-md-6 font-weight-bold">DHU %</div>
                        </div>
                        @foreach($report->dhu_reports ?? [] as $index => $dhu)
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <select name="dhu_floor[]" class="form-control">
                                    @foreach(['1st Floor', '2nd Floor', '3rd Floor', '4th Floor', '5th Floor'] as $floor)
                                        <option value="{{ $floor }}" 
                                            {{ $dhu['floor'] == $floor ? 'selected' : '' }}>
                                            {{ $floor }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="number" step="0.01" name="dhu_percentage[]" class="form-control"
                                       value="{{ $dhu['dhu_percentage'] }}">
                            </div>
                        </div>
                        @endforeach
                        <button type="button" class="btn btn-sm btn-info" id="add-dhu">
                            <i class="fas fa-plus"></i> Add DHU Record
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label>Remarkable Incident</label>
                    <textarea name="remarkable_incident" class="form-control" rows="3">{{ $report->remarkable_incident }}</textarea>
                </div>

                <div class="form-group">
                    <label>Improvement Area</label>
                    <textarea name="improvement_area" class="form-control" rows="3">{{ $report->improvement_area }}</textarea>
                </div>

                <div class="form-group">
                    <label>Other Information</label>
                    <textarea name="other_information" class="form-control" rows="3">{{ $report->other_information }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update Report</button>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Add shipment row
        document.getElementById('add-shipment').addEventListener('click', function() {
            const newRow = document.createElement('div');
            newRow.className = 'row mb-2';
            newRow.innerHTML = `
                <div class="col-md-4">
                    <input type="date" name="shipment_date[]" class="form-control">
                </div>
                <div class="col-md-4">
                    <input type="number" name="export_qty[]" class="form-control">
                </div>
                <div class="col-md-4">
                    <input type="number" step="0.01" name="export_value[]" class="form-control">
                </div>
            `;
            this.parentNode.insertBefore(newRow, this);
        });

        // Add DHU row
        document.getElementById('add-dhu').addEventListener('click', function() {
            const newRow = document.createElement('div');
            newRow.className = 'row mb-2';
            newRow.innerHTML = `
                <div class="col-md-6">
                    <select name="dhu_floor[]" class="form-control">
                        <option value="1st Floor">1st Floor</option>
                        <option value="2nd Floor">2nd Floor</option>
                        <option value="3rd Floor">3rd Floor</option>
                        <option value="4th Floor">4th Floor</option>
                        <option value="5th Floor">5th Floor</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="number" step="0.01" name="dhu_percentage[]" class="form-control">
                </div>
            `;
            this.parentNode.insertBefore(newRow, this);
        });
    </script>
    @endpush
</x-backend.layouts.master>
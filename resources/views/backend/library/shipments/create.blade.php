<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Create Shipments Report
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h4>Create Shipments Report</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('shipments.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Report Date</label>
                    <input type="date" name="report_date" class="form-control" required
                        value="{{ old('report_date', now()->format('Y-m-d')) }}">
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
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <input type="date" name="shipment_date[]" class="form-control"
                                    value="{{ now()->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-4">
                                <input type="number" name="export_qty[]" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <input type="number" step="0.01" name="export_value[]" class="form-control">
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-info" id="add-shipment">
                            <i class="fas fa-plus"></i> Add Shipment
                        </button>
                        <button type="button" class="btn btn-sm btn-danger remove-shipment">
                            <i class="fas fa-minus"></i> Remove Shipment
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label>Remarks</label>
                    <textarea name="remarks" class="form-control" rows="3"></textarea>
                </div>

                <a href="{{ route('shipments.index') }}" class="btn btn-secondary">Cancel</a>

                <button type="submit" class="btn btn-primary">Save Report</button>
            </form>
        </div>
    </div>


    <script>
        // Add shipment row
        document.getElementById('add-shipment').addEventListener('click', function() {
            const newRow = document.createElement('div');
            newRow.className = 'row mb-2';
            newRow.innerHTML = `
                <div class="col-md-4">
                    <input type="date" name="shipment_date[]" class="form-control" Value="{{ now()->format('Y-m-d') }}">
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

        // Add remove shipment functionality except the first row
        document.querySelector('.remove-shipment').addEventListener('click', function() {
            const rows = document.querySelectorAll('.row.mb-2');
            if (rows.length > 2) {
                rows[rows.length - 1].remove();
            } else {
                alert('At least one shipment row must remain.');
            }
        });
    </script>

</x-backend.layouts.master>

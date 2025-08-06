<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Edit Shipment
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h4>Edit Record</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('shipments.update', $shipment) }}" method="POST">
                @csrf @method('PUT')


                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h5>Shipment Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Report Date</label>
                            <input type="date" name="report_date" class="form-control" required
                                value="{{ old('report_date', $shipment->report_date->format('Y-m-d')) }}">
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4 font-weight-bold">Date</div>
                            <div class="col-md-4 font-weight-bold">Export Qty</div>
                            <div class="col-md-4 font-weight-bold">Export Value ($)</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <input type="date" name="shipment_date[]" class="form-control"
                                    value="{{ $shipment['shipment_date'] ? $shipment['shipment_date']->format('Y-m-d') : now()->format('Y-m-d') }}">
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
                    </div>
                </div>

                <div class="form-group">
                    <label>Remarks</label>
                    <textarea name="remarks" class="form-control" rows="3">{{ old('remarks', $shipment->remarks) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div> 
</x-backend.layouts.master>

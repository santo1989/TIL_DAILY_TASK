<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Create Daily Report
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h4>Create Daily Report</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('daily-reports.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label>Report Date</label>
                    <input type="date" name="report_date" class="form-control" required value="{{ now()->format('Y-m-d') }}">
                </div>

                <div class="form-group">
                    <label>Remarkable Incident</label>
                    <textarea name="remarkable_incident" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>Improvement Area</label>
                    <textarea name="improvement_area" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>Other Information</label>
                    <textarea name="other_information" class="form-control" rows="3"></textarea>
                </div>

                <a href="{{ route('daily-reports.index') }}" class="btn btn-secondary">Cancel</a>

                <button type="submit" class="btn btn-primary">Save Report</button>
            </form>
        </div>
    </div>

</x-backend.layouts.master>
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
</x-backend.layouts.master>
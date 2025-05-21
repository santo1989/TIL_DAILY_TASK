<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Edit Come Back Report
    </x-slot>

    <x-backend.layouts.elements.errors />
    
    <div class="card">
        <div class="card-header">
            <h4>Edit Report Entry</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('comeback.reports.update', $report->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Report Date</label>
                            <input type="date" name="report_date" class="form-control" 
                                   value="{{ old('report_date', $report->report_date) }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Employee ID</label>
                            <input type="text" name="employee_id" class="form-control" 
                                   value="{{ old('employee_id', $report->employee_id) }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" 
                                   value="{{ old('name', $report->name) }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Designation</label>
                            <input type="text" name="designation" class="form-control" 
                                   value="{{ old('designation', $report->designation) }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Floor</label>
                            <input type="text" name="floor" class="form-control" 
                                   value="{{ old('floor', $report->floor) }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Absent Days</label>
                            <input type="number" name="absent_days" class="form-control" 
                                   value="{{ old('absent_days', $report->absent_days) }}" min="1" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Reason</label>
                            <input type="text" name="reason" class="form-control" 
                                   value="{{ old('reason', $report->reason) }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Councilor Name</label>
                            <input type="text" name="councilor_name" class="form-control" 
                                   value="{{ old('councilor_name', $report->councilor_name) }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Remarks</label>
                    <textarea name="remarks" class="form-control">{{ old('remarks', $report->remarks) }}</textarea>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update Record</button>
                    <a href="{{ route('comeback.reports') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-backend.layouts.master>
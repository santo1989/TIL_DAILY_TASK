<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Edit Attendance Record
    </x-slot>

    <x-backend.layouts.elements.errors />
    
    <div class="card">
        <div class="card-header">
            <h4>Edit Attendance Record</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('attendance-reports.update', $report) }}" method="POST">
                @csrf @method('PUT')
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Employee ID</label>
                            <input type="text" name="employee_id" class="form-control" 
                                   value="{{ old('employee_id', $report->employee_id) }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" 
                                   value="{{ old('name', $report->name) }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Designation</label>
                            <input type="text" name="designation" class="form-control" 
                                   value="{{ old('designation', $report->designation) }}" required>
                        </div>
                    </div>
                </div>
    
                @if($report->type === 'late_comer')
                <div class="form-group">
                    <label>In Time</label>
                    <input type="time" name="in_time" class="form-control" 
                           value="{{ old('in_time', $report->in_time->format('H:i')) }}">
                </div>
                @else
                <div class="form-group">
                    <label>Reason</label>
                    <input type="text" name="reason" class="form-control" 
                           value="{{ old('reason', $report->reason) }}">
                </div>
                @endif
    
                <div class="form-group">
                    <label>Remarks</label>
                    <textarea name="remarks" class="form-control">{{ old('remarks', $report->remarks) }}</textarea>
                </div>
    
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</x-backend.layouts.master>
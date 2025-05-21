<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Edit Attendance Record
    </x-slot>

    <x-backend.layouts.elements.errors />
    
    <div class="card">
        <div class="card-header">
            <h5>Edit Attendance Record</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('attendance.summary.update', $attendanceSummary) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Report Date</label>
                            <input type="date" name="report_date" class="form-control" 
                                   value="{{ old('report_date', $attendanceSummary->report_date->format('Y-m-d')) }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Floor</label>
                            <input type="text" name="floor" class="form-control" 
                                   value="{{ old('floor', $attendanceSummary->floor) }}" required>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Onroll</label>
                            <input type="number" name="onroll" class="form-control" 
                                   value="{{ old('onroll', $attendanceSummary->onroll) }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Present</label>
                            <input type="number" name="present" class="form-control" 
                                   value="{{ old('present', $attendanceSummary->present) }}" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Absent</label>
                            <input type="number" name="absent" class="form-control" 
                                   value="{{ old('absent', $attendanceSummary->absent) }}" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Leave</label>
                            <input type="number" name="leave" class="form-control" 
                                   value="{{ old('leave', $attendanceSummary->leave) }}" required>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>ML</label>
                            <input type="number" name="ml" class="form-control" 
                                   value="{{ old('ml', $attendanceSummary->ml) }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea name="remarks" class="form-control">{{ old('remarks', $attendanceSummary->remarks) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update Record</button>
                    <a href="{{ route('attendance.summary') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-backend.layouts.master>
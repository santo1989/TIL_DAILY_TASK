<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Edit Operator Absent Record
    </x-slot>

    <x-backend.layouts.elements.errors />
    
    <div class="card">
        <div class="card-header">
            <h4>Edit Absent Record</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('operator-absent-analysis.update', $report->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Report Date</label>
                            <input type="date" name="report_date" class="form-control" 
                                   value="{{ old('report_date', $report->report_date)}}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Floor</label>
                            <select name="floor" class="form-control" required>
                                <option value="1st Floor" {{ $report->floor == '1st Floor' ? 'selected' : '' }}>1st Floor</option>
                                <option value="3rd Floor" {{ $report->floor == '3rd Floor' ? 'selected' : '' }}>3rd Floor</option>
                                <option value="4th Floor" {{ $report->floor == '4th Floor' ? 'selected' : '' }}>4th Floor</option>
                                <option value="5th Floor" {{ $report->floor == '5th Floor' ? 'selected' : '' }}>5th Floor</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Employee ID</label>
                            <input type="text" name="employee_id" class="form-control" 
                                   value="{{ old('employee_id', $report->employee_id) }}" required>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
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
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Join Date</label>
                            <input type="date" name="join_date" class="form-control" 
                                   value="{{ $report->join_date }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Line Number</label>
                            <input type="number" name="line" class="form-control" 
                                   value="{{ old('line', $report->line) }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Total Absent Days</label>
                            <input type="number" name="total_absent_days" class="form-control" 
                                   value="{{ old('total_absent_days', $report->total_absent_days) }}" required>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Last Present Date</label>
                            <input type="date" name="last_p_date" class="form-control" 
                                   value="{{ $report->last_p_date }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Come Back Date</label>
                            <input type="date" name="come_back" class="form-control" 
                                   value="{{  $report->come_back  }}">
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-9">
                        <div class="form-group">
                            <label>Absent Reason</label>
                            
                                <input type="text" name="absent_reason" class="form-control mt-2" 
                                       value="{{ $report->absent_reason }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Remarks</label>
                            <input type="text" name="remarks" class="form-control" 
                                   value="{{ old('remarks', $report->remarks) }}">
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update Record</button>
                    <a href="{{ route('operator-absent-analysis.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-backend.layouts.master>
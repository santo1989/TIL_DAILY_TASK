<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Edit Recruitment Summary
    </x-slot>

    <x-backend.layouts.elements.errors />
    
    <div class="card">
        <div class="card-header">
            <h4>Edit Recruitment Record</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('recruitment-summaries.update', $summary) }}" method="POST">
                @csrf @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Candidate Name</label>
                            <input type="Candidate" name="Candidate" class="form-control" 
                                   value="{{ old('Candidate', $summary->Candidate) }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Designation</label>
                            <select name="designation" class="form-control" required>
                                <option value="JSMO" {{ $summary->designation == 'JSMO' ? 'selected' : '' }}>JSMO</option>
                                <option value="Fin. Asst." {{ $summary->designation == 'Fin. Asst.' ? 'selected' : '' }}>Finance Assistant</option>
                                <option value="Jr. Iron Man" {{ $summary->designation == 'Jr. Iron Man' ? 'selected' : '' }}>Junior Iron Man</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Grade</label>
                            <input type="text" name="grade" class="form-control" 
                                   value="{{ old('grade', $summary->grade) }}">
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Salary</label>
                            <input type="number" name="salary" class="form-control" 
                                   value="{{ old('salary', $summary->salary) }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Joining Date</label>
                            <input type="date" name="probable_date_of_joining" class="form-control" 
                                   value="{{ old('probable_date_of_joining', $summary->probable_date_of_joining) }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Allocated Floor</label>
                            <select name="allocated_floor" class="form-control" required>
                                <option value="1st" {{ $summary->allocated_floor == '1st' ? 'selected' : '' }}>1st Floor</option>
                                <option value="3rd" {{ $summary->allocated_floor == '3rd' ? 'selected' : '' }}>3rd Floor</option>
                                <option value="5th" {{ $summary->allocated_floor == '5th' ? 'selected' : '' }}>5th Floor</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Interview Date</label>
                            <input type="date" class="form-control" 
                                   value="{{ $summary->interview_date->format('Y-m-d') }}" disabled>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Remarks</label>
                    <textarea name="remarks" class="form-control" rows="3">{{ old('remarks', $summary->remarks) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update Record</button>
                <a href="{{ route('recruitment-summaries.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</x-backend.layouts.master>
<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Edit OT Achievement
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h4>Edit Record</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('ot-achievements.update', $achievement) }}" method="POST">
                @csrf @method('PUT')
                
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>2H OT Persons</label>
                        <input type="number" name="two_hours_ot_persons" 
                               class="form-control" 
                               value="{{ old('two_hours_ot_persons', $achievement->two_hours_ot_persons) }}" 
                               required>
                    </div>
                    
                    <div class="col-md-4 form-group">
                        <label>Above 2H OT Persons</label>
                        <input type="number" name="above_two_hours_ot_persons" 
                               class="form-control" 
                               value="{{ old('above_two_hours_ot_persons', $achievement->above_two_hours_ot_persons) }}" 
                               required>
                    </div>
                    
                    <div class="col-md-4 form-group">
                        <label>Achievement</label>
                        <input type="number" step="0.01" name="achievement" 
                               class="form-control" 
                               value="{{ old('achievement', $achievement->achievement) }}" 
                               required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Remarks</label>
                    <textarea name="remarks" class="form-control" 
                              rows="3">{{ old('remarks', $achievement->remarks) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</x-backend.layouts.master>
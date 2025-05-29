<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Edit Floor Timing
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h4>Edit {{ $timing->floor }} Timing</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('floor-timings.update', $timing) }}" method="POST">
                @csrf @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Starting Time</label>
                            <input type="time" name="starting_time" 
                                   class="form-control" 
                                   value="{{ old('starting_time', $timing->starting_time->format('H:i')) }}" 
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label>Starting Responsible</label>
                            <textarea name="starting_responsible" class="form-control" 
                                      rows="3" required>@foreach($timing->starting_responsible as $person){{ $person['name'] }} ({{ $person['role'] }})@if(!$loop->last), @endif @endforeach</textarea>
                            <small class="text-muted">Format: Name (Role), Name (Role)</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Closing Time</label>
                            <input type="time" name="closing_time" 
                                   class="form-control" 
                                   value="{{ old('closing_time', $timing->closing_time->format('H:i')) }}" 
                                   required>
                        </div>
                        
                        <div class="form-group">
                            <label>Closing Responsible</label>
                            <textarea name="closing_responsible" class="form-control" 
                                      rows="3" required>@foreach($timing->closing_responsible as $person){{ $person['name'] }} ({{ $person['role'] }})@if(!$loop->last), @endif @endforeach</textarea>
                            <small class="text-muted">Format: Name (Role), Name (Role)</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Remarks</label>
                    <textarea name="remarks" class="form-control" 
                              rows="2">{{ old('remarks', $timing->remarks) }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</x-backend.layouts.master>
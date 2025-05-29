<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Edit Operation Detail
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h4>Edit {{ $detail->activity }}</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('operation-details.update', $detail) }}" method="POST">
                @csrf @method('PUT')
                
                <div class="row">
                    <div class="col-md-2 form-group">
                        <label>1st Floor</label>
                        <input type="number" step="0.001" name="floor_1" 
                               class="form-control" value="{{ $detail->floor_1 }}">
                    </div>
                    <div class="col-md-2 form-group">
                        <label>2nd Floor</label>
                        <input type="number" step="0.001" name="floor_2" 
                               class="form-control" value="{{ $detail->floor_2 }}">
                    </div>
                    <div class="col-md-2 form-group">
                        <label>3rd Floor</label>
                        <input type="number" step="0.001" name="floor_3" 
                               class="form-control" value="{{ $detail->floor_3 }}">
                    </div>
                    <div class="col-md-2 form-group">
                        <label>4th Floor</label>
                        <input type="number" step="0.001" name="floor_4" 
                               class="form-control" value="{{ $detail->floor_4 }}">
                    </div>
                    <div class="col-md-2 form-group">
                        <label>5th Floor</label>
                        <input type="number" step="0.001" name="floor_5" 
                               class="form-control" value="{{ $detail->floor_5 }}">
                    </div>
                    <div class="col-md-2 form-group">
                        <label>Total/Avg</label>
                        <input type="number" step="0.001" name="result" 
                               class="form-control" value="{{ $detail->result }}">
                    </div>
                </div>

                <div class="form-group">
                    <label>Remarks</label>
                    <textarea name="remarks" class="form-control">{{ $detail->remarks }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</x-backend.layouts.master>
<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Recruitment Summaries
    </x-slot>

    <x-backend.layouts.elements.errors />
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12 text-center">
                <h2>Recruitment Summary Report</h2>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-12 d-flex justify-content-between align-items-center flex-nowrap">
                    <!-- Left Group -->
                    <div class="d-flex align-items-center">
                        <a href="{{ route('home') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <a href="" class="btn btn-primary btn-sm ml-2">
                            <i class="fas fa-plus"></i> Add
                        </a>
                        <form action="{{ route('recruitment-summaries.index') }}" method="GET" class="form-inline ml-2">
                            <div class="d-flex flex-nowrap">
                                <select name="designation" class="form-control form-control-sm mr-2" style="width: 120px;" onchange="this.form.submit()">
                                    <!-- options -->
                                    <option value="">All Designations</option>
                            @foreach ($designations as $designation)
                                <option value="{{ $designation }}"
                                    {{ request('designation') == $designation ? 'selected' : '' }}>
                                    {{ $designation }}
                                </option>
                            @endforeach
                                </select>
                                <select name="floor" class="form-control form-control-sm mr-2" style="width: 100px;" onchange="this.form.submit()">
                                    <!-- options -->
                                    <option value="">All Floors</option>
                            @foreach ($floors as $floor)
                                <option value="{{ $floor }}" {{ request('floor') == $floor ? 'selected' : '' }}>
                                    {{ $floor }} Floor
                                </option>
                            @endforeach
                                </select>
                                <select name="selected" class="form-control form-control-sm mr-2" style="width: 130px;" onchange="this.form.submit()">
                                    <!-- options -->
                                    <option value="">All Candidates</option>
                            <option value="yes" {{ request('selected') == 'yes' ? 'selected' : '' }}>Selected
                            </option>
                            <option value="no" {{ request('selected') == 'no' ? 'selected' : '' }}>Not Selected
                            </option>
                                </select>
                                <input type="text" name="search" class="form-control form-control-sm" style="width: 150px;" placeholder="Search...">
                            </div>
                        </form>
                    </div>
        
                    <!-- Right Group -->
                    <div class="d-flex align-items-center">
                        <a href="{{ route('recruitment-summaries.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-sync"></i> Reset
                        </a>
                        <a href="{{ route('recruitment-summaries.download.template') }}" class="btn btn-success btn-sm ml-2">
                            <i class="fas fa-download"></i> Template
                        </a>
                        <button class="btn btn-info btn-sm ml-2" data-toggle="modal" data-target="#uploadModal">
                            <i class="fas fa-upload"></i> Upload
                        </button>
                    </div>
                </div>
            </div>
        </div>
    
        
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Candidate</th>
                            <th>Selected</th>
                            <th>Designation</th>
                            <th>Interview Date</th>
                            <th>Test Details</th>
                            <th>Salary</th>
                            <th>Joining Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($summaries as $summary)
                            <tr>
                                <td>
                                    {{ $loop->iteration + ($summaries->currentPage() - 1) * $summaries->perPage() }}
                                </td>
                                <td>{{ $summary->Candidate }}</td>
                                <td>{{ $summary->selected }}</td>
                                <td>{{ $summary->designation }}</td>
                                <td>
                                    @php
                                        $interviewDate = $summary->interview_date ? Carbon\Carbon::parse($summary->interview_date)->format('Y-m-d') : 'N/A';
                                    @endphp
                                    {{ $interviewDate }}
                                </td>
                                <td>
                                    <small class="text-muted">
                                        Floor: {{ $summary->test_taken_floor }}<br>
                                        By: {{ $summary->test_taken_by }}<br>
                                        {{-- Time: {{ $summary->test_taken_time ? $summary->test_taken_time : 'N/A' }}<br> --}}
                                       
                                    </small>
                                </td>
                                <td>৳{{ $summary->salary }}</td>
                                <td>
                                    @php
                                        $joiningDate = $summary->probable_date_of_joining ? Carbon\Carbon::parse($summary->probable_date_of_joining)->format('Y-m-d') : 'N/A';
                                    @endphp
                                    {{ $joiningDate }}

                                </td>
                                <td>
                                    <a href="{{ route('recruitment-summaries.edit', $summary) }}"
                                        class="btn btn-sm btn-warning">
                                        Edit
                                    </a>
                                    <form action="{{ route('recruitment-summaries.destroy', $summary) }}"
                                        method="POST" style="display: inline-block;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                {{ $summaries->links() }}
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Recruitment Summary</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('recruitment-summaries.upload') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Excel File</label>
                            <input type="file" name="excel_file" class="form-control-file" required>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Upload
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-backend.layouts.master>

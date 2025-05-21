<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Planning Data List
    </x-slot>

    <x-slot name='breadCrumb'>
        <x-backend.layouts.elements.breadcrumb>
            <x-slot name="pageHeader"> Planning Data </x-slot>

            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('planning_data.index') }}">Planning Data</a></li>
        </x-backend.layouts.elements.breadcrumb>
    </x-slot>

    <section class="content">
        <div class="container-fluid">
            @if (is_null($planning_data) || empty($planning_data))
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        <h1 class="text-danger"> <strong>Currently No Information Available!</strong> </h1>
                    </div>
                </div>
            @else
                {{-- <x-backend.layouts.elements.message /> --}}

                <x-backend.layouts.elements.errors />

                <div class="row">
                    <div class="col-12">
                        <div class="card" style="overflow-x:auto;">
                            <div class="card-header">
                                <!--back button-->
                                <a class="btn btn-outline-danger my-1 mx-1 inline btn-lg" href="{{ route('home') }}"><i
                                        class="bi bi-arrow-left"></i> Back</a>

                                @can('QC-CURD')
                                    <x-backend.form.anchor :href="route('planning_data.create')" type="create" />
                                @endcan
                                <a class="btn btn-outline-info my-1 mx-1 inline btn-lg"
                                    href={{ route('old_index') }}>Old Data</a>

                                <!-- Modal Start -->
                                <button type="button" class="btn btn-outline-primary my-1 mx-1 inline btn-lg"
                                    data-bs-toggle="modal" data-bs-target="#GenerateReport">
                                    Generate Report
                                </button>

                                <div class="modal fade" id="GenerateReport" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog text-center modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Generate Report
                                                </h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('generateReport') }}" method="POST">
                                                    @csrf
                                                    <div class="row">
                                                        @php
                                                            $buyers = DB::table('planning_data')
                                                                ->select('buyer_id', 'buyer')
                                                                ->distinct()
                                                                ->get();
                                                            $styles = DB::table('planning_data')
                                                                ->select('style')
                                                                ->distinct()
                                                                ->get();
                                                            $lines = DB::table('planning_data')
                                                                ->select('line')
                                                                ->distinct()
                                                                ->get();
                                                            $items = DB::table('planning_data')
                                                                ->select('item')
                                                                ->distinct()
                                                                ->get();
                                                        @endphp
                                                        <div class="col-4">
                                                            <div class="form-group mb-3">
                                                                <label for="buyer_id">Buyer</label>
                                                                <select class="form-control" id="buyer_id"
                                                                    name="buyer_id">
                                                                    <option value="">Select Buyer</option>
                                                                    @foreach ($buyers as $buyer)
                                                                        <option value="{{ $buyer->buyer_id }}">
                                                                            {{ $buyer->buyer }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group mb-3">
                                                                <label for="styles">Style</label>
                                                                <select class="form-control" id="styles"
                                                                    name="styles">
                                                                    <option value="">Select Style</option>
                                                                    @foreach ($styles as $style)
                                                                        <option value="{{ $style->style }}">
                                                                            {{ $style->style }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group mb-3">
                                                                <label for="item">Item</label>
                                                                <select class="form-control" id="item"
                                                                    name="item">
                                                                    <option value="">Select Item</option>
                                                                    @foreach ($items as $item)
                                                                        <option value="{{ $item->item }}">
                                                                            {{ $item->item }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group mb-3">
                                                                <label for="line">Line</label>
                                                                <select class="form-control" id="line"
                                                                    name="line">
                                                                    <option value="">Select Line</option>
                                                                    @foreach ($lines as $line)
                                                                        <option value="{{ $line->line }}">
                                                                            {{ $line->line }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group mb-3">
                                                                <label for="from_date">From Date</label>
                                                                <input type="date" class="form-control"
                                                                    id="from_date" name="from_date">
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="form-group mb-3">
                                                                <label for="to_date">To Date</label>
                                                                <input type="date" class="form-control"
                                                                    id="to_date" name="to_date">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <!--reset button for reset the form-->
                                                        <button type="reset" class="btn btn-outline-secondary">
                                                            <i class="bi bi-x"></i> Reset
                                                        </button>


                                                        <button type="button" class="btn btn-outline-secondary"
                                                            data-bs-dismiss="modal">
                                                            <i class="bi bi-x"></i> Close
                                                        </button>
                                                        <button type="submit" class="btn btn-outline-success">
                                                            <i class="bi bi-search"></i> Search
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Modal End -->


                            </div>


                            <!-- /.card-header -->
                            <div class="card-body" style="overflow-x:auto;">
                                {{-- planning_data Table goes here  --}}

                                <table id="datatablesSimple" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Sl#</th>
                                            <th>Date</th>
                                            <th>Floor</th>
                                            <th>Line</th> 
                                            <th>Buyer</th>
                                            <th>Style</th>
                                            <th>Item</th>
                                            <th>SMV</th>
                                            <th>Allocate Qty</th>
                                            <th>Sewing Start</th>
                                            <th>Sewing End</th>
                                            <th>Required Man Power</th>
                                            <th>Average Working Hour</th>
                                            <th>Expected Efficiency</th>
                                            <th>Remarks</th>
                                            <th>Actions</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $sl=0 @endphp
                                        {{-- @dd($planning_data); --}}
                                        @foreach ($planning_data as $key => $planning_data)
                                            <tr>
                                                <td>{{ ++$sl }}</td>
                                                <td>{{ \Carbon\Carbon::parse($planning_data->date)->format('d-M-Y') }}
                                                </td>
                                                <td> 
                                                        {{ $planning_data->floor }}{{ $planning_data->floor == 1 ? 'st' : ($planning_data->floor == 2 ? 'nd' : ($planning_data->floor == 3 ? 'rd' : 'th')) }}
                                                        Floor 
                                                </td>
                                                <th>{{ $planning_data->line }}</th>
                                                <th>{{ $planning_data->buyer }}</th> 
                                                <td>{{ $planning_data->style }}</td>
                                                <td>{{ $planning_data->item }}</td>
                                                <td> {{ $planning_data->smv }}</td>
                                                <td>{{ $planning_data->allocate_qty }}</td>
                                                <td>{{ \Carbon\Carbon::parse($planning_data->sewing_start_time)->format('d-M-Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($planning_data->sewing_end_time)->format('d-M-Y') }}</td>
                                                <td>{{ $planning_data->required_man_power }}</td>
                                                <td>{{ $planning_data->average_working_our }}</td>
                                                <td>{{ $planning_data->expected_efficiency }}</td>
                                                <td>{{ $planning_data->remarks }}
                                                </td>
                                                <td> 
                                                    @can('QC-EDIT')
                                                    <!-- Edit Button -->
                                                    <a href="{{ route('planning_data_edit',['planning_data'=>$planning_data->id]) }}" class="btn btn-outline-info my-1 mx-1 inline btn-sm"> <i class="bi bi-pencil"></i> Edit </a>
                                                        <form id="deleteForm"
                                                            action="{{ route('planning_data.line_destroy') }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <!-- Use DELETE method to match your route -->

                                                            <input type="hidden" name="date"
                                                                value="{{ $planning_data->date }}">
                                                            <input type="hidden" name="buyer_id"
                                                                value="{{ $planning_data->buyer_id }}">
                                                            <input type="hidden" name="floor"
                                                                value="{{ $planning_data->floor }}">
                                                            <input type="hidden" name="line"
                                                                value="{{ $planning_data->line }}">
                                                            <input type="hidden" name="style"
                                                                value="{{ $planning_data->style }}">
                                                            <input type="hidden" name="item"
                                                                value="{{ $planning_data->item }}">

                                                            <button type="button"
                                                                class="btn btn-outline-danger my-1 mx-1 inline btn-sm"
                                                                onclick="confirmDeleteLine()">
                                                                <i class="bi bi-trash"></i>
                                                                Delete
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->


                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    @endif

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmDelete(url) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form if the user confirms
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.innerHTML = `@csrf @method('delete')`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function confirmDeleteLine() {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the existing form if the user confirms
                    document.getElementById('deleteForm').submit();
                }
            });
        }

        // Reset the form when the modal is closed
        $('#GenerateReport').on('hidden.bs.modal', function() {
            $(this).find('form').trigger('reset');
        });
        $(document).ready(function() {
            $('#buyer_id').change(function() {
                var buyer_id = $(this).val();
                if (buyer_id) {
                    $.ajax({
                        url: "{{ route('search') }}",
                        method: "GET",
                        data: {
                            search: 'buyer_id',
                            buyer_id: buyer_id
                        },
                        success: function(data) {
                            var styleOptions = '<option value="">Select Style</option>';
                            var itemOptions = '<option value="">Select Item</option>';
                            var lineOptions = '<option value="">Select Line</option>';

                            if (data.styles) {
                                data.styles.forEach(function(item) {
                                    styleOptions += '<option value="' + item.style +
                                        '">' + item.style + '</option>';
                                });
                            }
                            if (data.items) {
                                data.items.forEach(function(item) {
                                    itemOptions += '<option value="' + item.item +
                                        '">' + item.item + '</option>';
                                });
                            }
                            if (data.lines) {
                                data.lines.forEach(function(item) {
                                    lineOptions += '<option value="' + item.line +
                                        '">' + item.line + '</option>';
                                });
                            }

                            $('#styles').html(styleOptions);
                            $('#item').html(itemOptions);
                            $('#line').html(lineOptions);
                        }
                    });
                }
            });

            $('#styles').change(function() {
                var style = $(this).val();
                if (style) {
                    $.ajax({
                        url: "{{ route('search') }}",
                        method: "GET",
                        data: {
                            search: 'style',
                            style: style
                        },
                        success: function(data) {
                            var itemOptions = '<option value="">Select Item</option>';
                            var lineOptions = '<option value="">Select Line</option>';

                            if (data.items) {
                                data.items.forEach(function(item) {
                                    itemOptions += '<option value="' + item.item +
                                        '">' + item.item + '</option>';
                                });
                            }
                            if (data.lines) {
                                data.lines.forEach(function(item) {
                                    lineOptions += '<option value="' + item.line +
                                        '">' + item.line + '</option>';
                                });
                            }

                            $('#item').html(itemOptions);
                            $('#line').html(lineOptions);
                        }
                    });
                }
            });

            $('#item').change(function() {
                var item = $(this).val();
                if (item) {
                    $.ajax({
                        url: "{{ route('search') }}",
                        method: "GET",
                        data: {
                            search: 'item',
                            item: item
                        },
                        success: function(data) {
                            var lineOptions = '<option value="">Select Line</option>';

                            if (data.lines) {
                                data.lines.forEach(function(item) {
                                    lineOptions += '<option value="' + item.line +
                                        '">' + item.line + '</option>';
                                });
                            }

                            $('#line').html(lineOptions);
                        }
                    });
                }
            });
        });

        //update index page with the latest data without refreshing the page
    </script>
    {{-- <script>
        setInterval(function() {
            $.ajax({
                url: "{{ route('planning_data.index') }}",
                method: "GET",
                success: function(data) {
                    $('#datatablesSimple').html(data); // Replace table content with the updated data
                    console.log('Data updated successfully');
                },
                error: function(xhr) {
                    console.error("An error occurred: " + xhr.statusText);
                }
            });
        }, 10000); // Poll every 10 seconds  
    </script> --}}
</x-backend.layouts.master>

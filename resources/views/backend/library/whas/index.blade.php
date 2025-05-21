<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Working Hour Allocation Data List
    </x-slot>

    <x-slot name='breadCrumb'>
        <x-backend.layouts.elements.breadcrumb>
            <x-slot name="pageHeader"> Working Hour Allocation Data </x-slot>

            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('planning_data.index') }}">Hourly Production
                    Dashboard Data</a></li>
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
                                                            $buyers = DB::table('working_hour_allocations')
                                                                ->select('buyer_id', 'buyer')
                                                                ->distinct()
                                                                ->get();
                                                            $styles = DB::table('working_hour_allocations')
                                                                ->select('style')
                                                                ->distinct()
                                                                ->get();
                                                            $lines = DB::table('working_hour_allocations')
                                                                ->select('line')
                                                                ->distinct()
                                                                ->get();
                                                            $items = DB::table('working_hour_allocations')
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
                            <div class="card-body">
                                {{-- planning_data Table goes here  --}}

                                <table id="datatablesSimple" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Sl#</th>
                                            <th>Date</th>
                                            <th>Floor</th>
                                            <th>Line</th>
                                            <th>Section</th>
                                            <th>Buyer</th>
                                            <th>Style</th>
                                            <th>Item</th>
                                            <th>Hours</th>
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
                                                    <!-- Floor Modal Start -->
                                                    <!-- Button trigger modal -->
                                                    <button type="button" class="btn btn-outline-info"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#FloorModal{{ $key }}"
                                                        data-buyer="{{ $planning_data->buyer }}"
                                                        data-style="{{ $planning_data->style }}"
                                                        data-item="{{ $planning_data->item }}"
                                                        data-line="{{ $planning_data->line }}"
                                                        data-date="{{ $planning_data->date }}"
                                                        data-floor="{{ $planning_data->floor }}">
                                                        {{ $planning_data->floor }}{{ $planning_data->floor == 1 ? 'st' : ($planning_data->floor == 2 ? 'nd' : ($planning_data->floor == 3 ? 'rd' : 'th')) }}
                                                        Floor
                                                    </button>

                                                    <!-- Modal -->
                                                    <div class="modal fade" id="FloorModal{{ $key }}"
                                                        data-buyer="{{ $planning_data->buyer }}"
                                                        data-style="{{ $planning_data->style }}"
                                                        data-item="{{ $planning_data->item }}"
                                                        data-date="{{ $planning_data->date }}"
                                                        data-floor="{{ $planning_data->floor }}"
                                                        data-line="{{ $planning_data->line }}"
                                                        data-bs-backdrop="static" data-bs-keyboard="false"
                                                        tabindex="-1" aria-labelledby="staticBackdropLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-fullscreen">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5"
                                                                        id="staticBackdropLabel">
                                                                        Floor {{ $planning_data->floor }} -
                                                                        {{ $planning_data->buyer }},
                                                                        {{ $planning_data->style }},
                                                                        {{ $planning_data->item }},{{ $planning_data->line }}
                                                                        -
                                                                        {{ \Carbon\Carbon::parse($planning_data->date)->format('d-M-Y') }}
                                                                    </h1>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <!-- Display information for the current row -->
                                                                    <table class="table table-borderless table-hover">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Date</th>
                                                                                <th>Floor</th>
                                                                                <th>Buyer</th>
                                                                                <th>Style</th>
                                                                                <th>Item</th>
                                                                                <th>Line</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>{{ \Carbon\Carbon::parse($planning_data->date)->format('d-M-Y') }}
                                                                                </td>
                                                                                <td>{{ $planning_data->floor }}{{ $planning_data->floor == 1 ? 'st' : ($planning_data->floor == 2 ? 'nd' : ($planning_data->floor == 3 ? 'rd' : 'th')) }}
                                                                                    Floor</td>
                                                                                <td>{{ $planning_data->buyer }}
                                                                                </td>
                                                                                <td>{{ $planning_data->style }}
                                                                                </td>
                                                                                <td>{{ $planning_data->item }}
                                                                                </td>
                                                                                <td>{{ $planning_data->line }}
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                    <!-- Display lines for the current row -->

                                                                    <!-- Display lines for the current row start -->

                                                                    <div class="card" style="overflow-x:auto;"
                                                                        id="planning_dataTable">
                                                                        @php
                                                                            $planning_dataLines = DB::table(
                                                                                'working_hour_allocations',
                                                                            )
                                                                                ->where('date', now()->format('Y-m-d'))
                                                                                ->where('floor', $planning_data->floor)
                                                                                ->where('buyer', $planning_data->buyer)
                                                                                ->where('style', $planning_data->style)
                                                                                ->where('item', $planning_data->item)
                                                                                ->where('line', $planning_data->line)
                                                                                ->distinct('start_time', 'end_time')
                                                                                ->get();
                                                                            // dd($planning_dataLines);
                                                                        @endphp


                                                                        <!-- card-body -->
                                                                        <div class="card-body">
                                                                            <!-- planning_data Table goes here -->
                                                                            <h3 class="text-center"><strong>Alter
                                                                                    Data</strong> </h3>
                                                                            <table class="table table-bordered pb-2">
                                                                                <thead>
                                                                                    <tr>

                                                                                        <th>
                                                                                            @can('QC-EDIT')
                                                                                                Action
                                                                                            @endcan
                                                                                        </th>


                                                                                        <th>Hours</th>
                                                                                        <th>Sewing DHU%</th>
                                                                                        <th>Total Production</th>
                                                                                        <th>Total Alter</th>
                                                                                        <th>Total Check</th>
                                                                                        <th>Uneven Shape</th>
                                                                                        <th>Broken Stitch</th>
                                                                                        <th>Dirty Mark</th>
                                                                                        <th>Oil Stain</th>
                                                                                        <th>Down Stitch</th>
                                                                                        <th>Hiking</th>
                                                                                        <th>Improper Tuck</th>
                                                                                        <th>Label Alter</th>
                                                                                        <th>Needle Mark / Hole</th>
                                                                                        <th>Open Seam</th>
                                                                                        <th>Skip Stitch</th>
                                                                                        <th>Pleat</th>
                                                                                        <th>Sleeve / Shoulder Up
                                                                                            Down
                                                                                        </th>
                                                                                        <th>Puckering</th>
                                                                                        <th>Raw Edge</th>
                                                                                        <th>Shading</th>
                                                                                        <th>Others</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach ($planning_dataLines as $planning_dataLine)
                                                                                        <tr>
                                                                                            <td> @can('QC-EDIT')
                                                                                                    <form
                                                                                                        action="{{ route('planning_data.edit', ['wha' => $planning_dataLine->id]) }}"
                                                                                                        method="get">
                                                                                                        @csrf
                                                                                                        <input
                                                                                                            type="hidden"
                                                                                                            name="wha"
                                                                                                            value="{{ $planning_dataLine->id }}">
                                                                                                        <button
                                                                                                            class="btn btn-outline-success my-1 mx-1  btn-sm"
                                                                                                            type="submit"><i
                                                                                                                class="bi bi-pencil"></i>
                                                                                                            Edit</button>
                                                                                                    </form>
                                                                                                    <button
                                                                                                        class="btn btn-outline-danger my-1 mx-1 inline btn-sm"
                                                                                                        onclick="confirmDelete('{{ route('planning_data.destroy', ['planning_data' => $planning_dataLine->id]) }}')">
                                                                                                        <i
                                                                                                            class="bi bi-trash"></i>
                                                                                                        Delete
                                                                                                    </button>
                                                                                                @endcan
                                                                                            </td>

                                                                                            <td>{{ $planning_dataLine->start_time }}
                                                                                                -
                                                                                                {{ $planning_dataLine->end_time }}
                                                                                            </td>
                                                                                            <td>
                                                                                                @php
                                                                                                    $sewing_dhu =
                                                                                                        $planning_dataLine->sewing_dhu;
                                                                                                    // show only 2 decimal points
                                                                                                    $sewing_dhu = number_format(
                                                                                                        $sewing_dhu,
                                                                                                        2,
                                                                                                    );
                                                                                                    echo $sewing_dhu;
                                                                                                @endphp
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->Total_Production }}
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->Total_Alter }}
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->total_check }}
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->Uneven_Shape }}
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->Broken_Stitch }}
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->Dirty_Mark }}
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->Oil_Stain }}
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->Down_Stitch }}
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->Hiking }}
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->Improper_Tuck }}
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->Label_Alter }}
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->Needle_Mark_Hole }}
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->Open_Seam }}
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->Skip_Stitch }}
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->Pleat }}
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->Sleeve_Shoulder_Up_Down }}
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->Puckering }}
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->Raw_Edge }}
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->Shading }}
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->Others }}
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                    <tr>
                                                                                        <td colspan="2">
                                                                                            <strong>Total</strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $sewing_dhu = $planning_dataLines->avg(
                                                                                                        'sewing_dhu',
                                                                                                    );
                                                                                                    // show only 2 decimal points
                                                                                                    $sewing_dhu = number_format(
                                                                                                        $sewing_dhu,
                                                                                                        2,
                                                                                                    );
                                                                                                    echo $sewing_dhu;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $total_production = $planning_dataLines->sum(
                                                                                                        'Total_Production',
                                                                                                    );
                                                                                                    echo $total_production;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $total_alter = $planning_dataLines->sum(
                                                                                                        'Total_Alter',
                                                                                                    );
                                                                                                    echo $total_alter;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $total_check = $planning_dataLines->sum(
                                                                                                        'total_check',
                                                                                                    );
                                                                                                    echo $total_check;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $uneven_shape = $planning_dataLines->sum(
                                                                                                        'Uneven_Shape',
                                                                                                    );
                                                                                                    echo $uneven_shape;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $broken_stitch = $planning_dataLines->sum(
                                                                                                        'Broken_Stitch',
                                                                                                    );
                                                                                                    echo $broken_stitch;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $dirty_mark = $planning_dataLines->sum(
                                                                                                        'Dirty_Mark',
                                                                                                    );
                                                                                                    echo $dirty_mark;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $oil_stain = $planning_dataLines->sum(
                                                                                                        'Oil_Stain',
                                                                                                    );
                                                                                                    echo $oil_stain;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $down_stitch = $planning_dataLines->sum(
                                                                                                        'Down_Stitch',
                                                                                                    );
                                                                                                    echo $down_stitch;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $hiking = $planning_dataLines->sum(
                                                                                                        'Hiking',
                                                                                                    );
                                                                                                    echo $hiking;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $improper_tuck = $planning_dataLines->sum(
                                                                                                        'Improper_Tuck',
                                                                                                    );
                                                                                                    echo $improper_tuck;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $label_alter = $planning_dataLines->sum(
                                                                                                        'Label_Alter',
                                                                                                    );
                                                                                                    echo $label_alter;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $needle_mark_hole = $planning_dataLines->sum(
                                                                                                        'Needle_Mark_Hole',
                                                                                                    );
                                                                                                    echo $needle_mark_hole;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $open_seam = $planning_dataLines->sum(
                                                                                                        'Open_Seam',
                                                                                                    );
                                                                                                    echo $open_seam;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $skip_stitch = $planning_dataLines->sum(
                                                                                                        'Skip_Stitch',
                                                                                                    );
                                                                                                    echo $skip_stitch;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $pleat = $planning_dataLines->sum(
                                                                                                        'Pleat',
                                                                                                    );
                                                                                                    echo $pleat;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $sleeve_shoulder_up_down = $planning_dataLines->sum(
                                                                                                        'Sleeve_Shoulder_Up_Down',
                                                                                                    );
                                                                                                    echo $sleeve_shoulder_up_down;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $puckering = $planning_dataLines->sum(
                                                                                                        'Puckering',
                                                                                                    );
                                                                                                    echo $puckering;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $raw_edge = $planning_dataLines->sum(
                                                                                                        'Raw_Edge',
                                                                                                    );
                                                                                                    echo $raw_edge;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $shading = $planning_dataLines->sum(
                                                                                                        'Shading',
                                                                                                    );
                                                                                                    echo $shading;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $others = $planning_dataLines->sum(
                                                                                                        'Others',
                                                                                                    );
                                                                                                    echo $others;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                    </tr>


                                                                                </tbody>
                                                                            </table>
                                                                            <h3 class="text-center"><strong>Reject
                                                                                    Data</strong> </h3>
                                                                            <table class="table table-bordered pb-2">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Hours</th>
                                                                                        <th>Reject %</th>
                                                                                        <th>Total Reject</th>
                                                                                        <th>Fabric_hole </th>
                                                                                        <th>Scissor/ Cuttar Cut</th>
                                                                                        <th>Needle Hole</th>
                                                                                        <th>Print/ EMB Damage</th>
                                                                                        <th>Shading</th>
                                                                                        <th>Others</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach ($planning_dataLines as $planning_dataLine)
                                                                                        <tr>
                                                                                            <td>{{ $planning_dataLine->start_time }}
                                                                                                -
                                                                                                {{ $planning_dataLine->end_time }}
                                                                                            </td>
                                                                                            <td>
                                                                                                @php
                                                                                                    $reject_dhu =
                                                                                                        $planning_dataLine->reject_dhu;
                                                                                                    // show only 2 decimal points
                                                                                                    $reject_dhu = number_format(
                                                                                                        $reject_dhu,
                                                                                                        2,
                                                                                                    );
                                                                                                    echo $reject_dhu;
                                                                                                @endphp
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->Total_reject }}
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->reject_Fabric_hole }}
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->reject_scissor_cuttar_cut }}
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->reject_Needle_hole }}
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->reject_Print_emb_damage }}
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->reject_Shading }}
                                                                                            </td>
                                                                                            <td>{{ $planning_dataLine->reject_Others }}

                                                                                        </tr>
                                                                                    @endforeach
                                                                                    <tr>
                                                                                        <td><strong>Total</strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $reject_dhu = $planning_dataLines->avg(
                                                                                                        'reject_dhu',
                                                                                                    );
                                                                                                    // show only 2 decimal points
                                                                                                    $reject_dhu = number_format(
                                                                                                        $reject_dhu,
                                                                                                        2,
                                                                                                    );
                                                                                                    echo $reject_dhu;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $Total_reject = $planning_dataLines->sum(
                                                                                                        'Total_reject',
                                                                                                    );
                                                                                                    echo $Total_reject;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $reject_Fabric_hole = $planning_dataLines->sum(
                                                                                                        'reject_Fabric_hole',
                                                                                                    );
                                                                                                    echo $reject_Fabric_hole;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $reject_scissor_cuttar_cut = $planning_dataLines->sum(
                                                                                                        'reject_scissor_cuttar_cut',
                                                                                                    );
                                                                                                    echo $reject_scissor_cuttar_cut;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $reject_Needle_hole = $planning_dataLines->sum(
                                                                                                        'reject_Needle_hole',
                                                                                                    );
                                                                                                    echo $reject_Needle_hole;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $reject_Print_emb_damage = $planning_dataLines->sum(
                                                                                                        'reject_Print_emb_damage',
                                                                                                    );
                                                                                                    echo $reject_Print_emb_damage;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $reject_Shading = $planning_dataLines->sum(
                                                                                                        'reject_Shading',
                                                                                                    );
                                                                                                    echo $reject_Shading;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>
                                                                                        <td><strong>
                                                                                                @php
                                                                                                    $reject_Others = $planning_dataLines->sum(
                                                                                                        'reject_Others',
                                                                                                    );
                                                                                                    echo $reject_Others;
                                                                                                @endphp
                                                                                            </strong>
                                                                                        </td>

                                                                                    </tr>


                                                                                </tbody>
                                                                            </table>





                                                                        </div>
                                                                        <!-- /.card-body -->
                                                                    </div>

                                                                    <!-- Display lines for the current row end-->

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button"
                                                                        class="btn btn-outline-secondary"
                                                                        data-bs-dismiss="modal">Close</button>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Floor Modal End -->
                                                </td>
                                                <th>{{ $planning_data->line }}</th>
                                                <th>{{ $planning_data->section }}</th>
                                                <td>{{ $planning_data->buyer }}</td>
                                                <td>{{ $planning_data->style }}</td>
                                                <td>{{ $planning_data->item }}</td>
                                                <td>
                                                    @php
                                                        $hpd_timeEntry = DB::table('working_hour_allocations')
                                                            ->where('date', now()->format('Y-m-d'))
                                                            ->where('floor', $planning_data->floor)
                                                            ->where('buyer', $planning_data->buyer)
                                                            ->where('style', $planning_data->style)
                                                            ->where('item', $planning_data->item)
                                                            ->where('line', $planning_data->line)
                                                            ->distinct('start_time', 'end_time')
                                                            ->get();
                                                        // dd($hpd_timeEntry->count());
                                                        // dd($hpd_timeEntry);
                                                    @endphp
                                                    @if ($hpd_timeEntry->count() > 0)
                                                        @foreach ($hpd_timeEntry as $timeEntry)
                                                            {{-- @dd($timeEntry); --}}
                                                            @if ($timeEntry->Total_Alter == null)
                                                                <form
                                                                    action="{{ route('planning_data_timeEntry', ['planning_data_timeEntry_id' => $timeEntry->id]) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <input type="hidden"
                                                                        name="planning_data_timeEntry_id"
                                                                        value="{{ $timeEntry->id }}">
                                                                    <button
                                                                        class="btn btn-outline-success my-1 mx-1  btn-sm"
                                                                        type="submit">{{ $timeEntry->start_time }}</button>
                                                                </form>
                                                            @else
                                                                {{ $timeEntry->start_time }}, &nbsp;
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        // Query to get the latest entry for the given conditions
                                                        $hpd_timeEntry = DB::table('working_hour_allocations')
                                                            ->where('date', now()->format('Y-m-d'))
                                                            ->where('floor', $planning_data->floor)
                                                            ->where('buyer', $planning_data->buyer)
                                                            ->where('style', $planning_data->style)
                                                            ->where('item', $planning_data->item)
                                                            ->where('line', $planning_data->line)
                                                            ->latest('id')
                                                            ->first(); // Use first() to get the single latest record
                                                    @endphp

                                                    @if ($hpd_timeEntry)
                                                        @if ($hpd_timeEntry->Total_Alter === null)
                                                        @else
                                                            <form
                                                                action="{{ route('planning_data_timeEntry_copy', ['planning_data_timeEntry_id' => $hpd_timeEntry->id]) }}"
                                                                method="POST">
                                                                @csrf
                                                                <input type="hidden"
                                                                    name="planning_data_timeEntry_id"
                                                                    value="{{ $hpd_timeEntry->id }}">
                                                                <button
                                                                    class="btn btn-outline-success my-1 mx-1 btn-sm"
                                                                    type="submit">Add New</button>
                                                            </form>
                                                        @endif
                                                    @endif
                                                    @can('QC-EDIT')
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

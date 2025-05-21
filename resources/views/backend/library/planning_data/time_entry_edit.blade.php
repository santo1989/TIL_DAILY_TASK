<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Planning Data Inforomation
    </x-slot>

    <x-slot name='breadCrumb'>
        <x-backend.layouts.elements.breadcrumb>
            <x-slot name="pageHeader"> Planning Data </x-slot>

            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('hourlyProductionDashboards.index') }}">Hourly Production
                    Dashboard Data</a></li>
        </x-backend.layouts.elements.breadcrumb>
    </x-slot>

    <section class="content">
        <div class="container-fluid">

            @if (session('message'))
                <div class="alert alert-success">
                    <span class="close" data-dismiss="alert">&times;</span>
                    <strong>{{ session('message') }}.</strong>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <table class="table table-borderless table-hover">
                                <thead>
                                    {{-- @dd($hourlyProductionDashboard)
                                    @die --}}
                                    <tr>
                                        <th class="text-center">Date</th>
                                        <td class="text-left">
                                            {{ \Carbon\Carbon::parse($hourlyProductionDashboard->date)->format('d-M-Y') }}
                                        </td>

                                        <th class="text-center">Floor</th>
                                        <td class="text-left">
                                            {{ $hourlyProductionDashboard->floor }}{{ $hourlyProductionDashboard->floor == 1 ? 'st' : ($hourlyProductionDashboard->floor == 2 ? 'nd' : ($hourlyProductionDashboard->floor == 3 ? 'rd' : 'th')) }}
                                            Floor</td>


                                        <th class="text-center">Block</th>
                                        <td class="text-left">{{ $hourlyProductionDashboard->block }}
                                        </td>
                                    </tr>
                                </thead>

                            </table>
                        </div>
                        <!-- /.card-header -->

                        <!-- card-body -->
                        <div class="card-body">
                            <!-- hourlyProductionDashboard Table goes here -->
                            <form action="{{ route('time_entry_update', $hourlyProductionDashboard->id) }}"
                                method="POST">
                                @csrf
                                @method('POST')


                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>Time</th>
                                            <th>Production Achivement</th>
                                            <th>Target</th>
                                            <th>Quality Check (PCs)</th>
                                            <th>Defact Qty</th>
                                            <th>Bottlenecks Machine</th>
                                            <th>Bottlenecks Remarks</th>
                                            <th>Update Production Details</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <input type="hidden" name="batch_id"
                                                value="{{ $hourlyProductionDashboard->batch_id }}">
                                            <td>
                                                <input type="number" name="hourly_production_dashboard_id"
                                                    value="{{ $hourlyProductionDashboard->id }}" readonly
                                                    class="form-control">
                                            </td>
                                            <td>
                                                <input type="time" name="productions_start_hours"
                                                    value="{{ \Carbon\Carbon::parse($hourlyProductionDashboard->productions_start_hours)->format('H:i') ?? '' }}"
                                                    class="form-control" required readonly>

                                            </td>

                                            <td>
                                                <input type="number" name="output_hour"
                                                    value="{{ ceil($hourlyProductionDashboard->output_hour) ?? '' }}"
                                                    class="form-control" required>
                                            </td>
                                            <td>
                                                <input type="number" name="target_per_hour"
                                                    value="{{ ceil($hourlyProductionDashboard->target_per_hour) ?? '' }}"
                                                    class="form-control" required readonly>
                                            </td>
                                            <td>
                                                <input type="number" name="quality_check_pcs"
                                                    value="{{ $hourlyProductionDashboard->quality_check_pcs ?? '' }}"
                                                    class="form-control" required>
                                            </td>
                                            <td>
                                                <input type="number" name="defect_qty_pcs"
                                                    value="{{ $hourlyProductionDashboard->defect_qty_pcs ?? '' }}"
                                                    class="form-control" required>
                                            </td>
                                            <td>

                                                <textarea name="Bottlenecks_machine" id="" cols="20" rows="10"> {{ $hourlyProductionDashboard->hourly_bottlenecks_machine ?? '' }} </textarea>
                                            </td>
                                            <td>
                                                <textarea name="Bottlenecks_remarks" id="" cols="20" rows="10"> {{ $hourlyProductionDashboard->hourly_bottlenecks_remarks ?? '' }} </textarea>
                                            </td>
                                            <td>
                                                <br>
                                                <label for="smv">SMV</label>
                                                <input type="text" class="form-control" name="smv" id="smv"
                                                    value="{{ number_format($hourlyProductionDashboard->smv, 2) }}"
                                                    required>

                                                </br>
                                                <br>
                                                <label for="running_machine">Running
                                                    M/C</label>
                                                <input type="number" class="form-control" name="running_machine"
                                                    id="running_machine"
                                                    value="{{ $hourlyProductionDashboard->running_machine }}" required>
                                                </br>
                                                <br>
                                                <label for="operator">Oprator</label>
                                                <input type="number" class="form-control" name="operator"
                                                    id="operator" value="{{ $hourlyProductionDashboard->operator }}"
                                                    required>
                                                </br>
                                                <br>
                                                <label for="helper">Helper</label>
                                                <input type="number" class="form-control" name="helper" id="helper"
                                                    value="{{ $hourlyProductionDashboard->helpar }}" required>
                                                </br>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table> <button type="submit" class="btn btn-outline-info">Save
                                    changes</button>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    <div class="card-footer">

                        <a href="{{ route('hourlyProductionDashboards.index') }}" class="btn btn-outline-danger"><i
                                class="fas fa-backward"></i> Back</a>
                    </div>
                    </form>

                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</x-backend.layouts.master>

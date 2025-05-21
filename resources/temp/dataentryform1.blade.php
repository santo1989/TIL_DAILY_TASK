<x-backend.layouts.master>
    <x-slot name="pageTitle">
        KPI Data Entry Form
    </x-slot>

    <x-slot name='breadCrumb'>
        <x-backend.layouts.elements.breadcrumb>
            <x-slot name="pageHeader"> KPI Data Entry Form </x-slot>

            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('supervisor_assigns.index') }}">KPI Data Entry Form </a></li>
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

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            {{-- supervisor_assign Table goes here --}}
                            <table id="myTable">
                                <thead>
                                    <tr>
                                        <th>KRA</th>
                                        <th>KPI</th>
                                        <th>Weight of KPIs</th>
                                        <th>Benchmark Target</th>
                                        <th>Achievement</th>
                                        <th>Sources of Information</th>
                                        <th>Score</th>
                                        <th>Weight Value</th>
                                        <th>Justification</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kras as $kra)
                                        <tr>
                                            <td>
                                                <select name="kra[]" class="form-control kra">
                                                    <option value="{{ $kra->id }}">{{ $kra->name }}</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="kpi[]" class="form-control kpi">
                                                    <option value="">Select KPI</option>
                                                    @foreach ($kra->kpis as $kpi)
                                                        <option value="{{ $kpi->id }}">{{ $kpi->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="text" name="weight_of_kpis[]"
                                                    class="form-control weight_of_kpis" /></td>
                                            <td><input type="text" name="bench_mark_target[]"
                                                    class="form-control bench_mark_target" /></td>
                                            <td><input type="text" name="achivement[]"
                                                    class="form-control achivement" /></td>
                                            <td><input type="text" name="sources_of_informations[]"
                                                    class="form-control sources_of_informations" /></td>
                                            <td><input type="text" name="score[]" class="form-control score" /></td>
                                            <td><input type="text" name="weight_value[]"
                                                    class="form-control weight_value" /></td>
                                            <td><input type="text" name="justification[]"
                                                    class="form-control justification" /></td>
                                            <td><button type="button" name="add" id="add"
                                                    class="btn btn-success">Add More</button></td>
                                        </tr>
                                    @break
                                @endforeach
                            </tbody>
                        </table>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        var rowCounter = {{ count($kras) }};
        var kpis = {!! json_encode($kras->pluck('kpis', 'id')) !!};

        $('#myTable').on('change', '.kra', function() {
            var kpiDropdown = $(this).closest('tr').find('.kpi');
            var selectedKRA = $(this).val();

            kpiDropdown.empty();
            kpiDropdown.append('<option value="">Select KPI</option>');

            if (kpis[selectedKRA]) {
                $.each(kpis[selectedKRA], function(index, value) {
                    kpiDropdown.append('<option value="' + value.id + '">' + value.name +
                        '</option>');
                });
            }
        });

        $('#add').click(function() {
            rowCounter++;

            var newRow = '<tr>' +
                '<td>' +
                '<select name="kra[]" class="form-control kra">' +
                '<option value="">Select KRA</option>' +
                @foreach ($kras as $kra)
                    '<option value="{{ $kra->id }}">{{ $kra->name }}</option>' +
                @endforeach
            '</select>' +
            '</td>' +
            '<td>' +
            '<select name="kpi[]" class="form-control kpi">' +
            '<option value="">Select KPI</option>' +
            '</select>' +
            '</td>' +
            '<td><input type="text" name="weight_of_kpis[]" class="form-control weight_of_kpis" /></td>' +
            '<td><input type="text" name="bench_mark_target[]" class="form-control bench_mark_target" /></td>' +
            '<td><input type="text" name="achivement[]" class="form-control achivement" /></td>' +
            '<td><input type="text" name="sources_of_informations[]" class="form-control sources_of_informations" /></td>' +
            '<td><input type="text" name="score[]" class="form-control score" /></td>' +
            '<td><input type="text" name="weight_value[]" class="form-control weight_value" /></td>' +
            '<td><input type="text" name="justification[]" class="form-control justification" /></td>' +
            '<td><button type="button" name="remove" id="' + rowCounter +
                '" class="btn btn-danger btn_remove">X</button></td>' +
                '</tr>';

            $('#myTable').append(newRow);
        });

        $(document).on('click', '.btn_remove', function() {
            var button_id = $(this).attr("id");
            $('#row' + button_id + '').remove();
        });

        $('#submit').click(function() {
            $.ajax({
                url: "{{ route('submit-form') }}",
                method: "POST",
                data: $('#add_name').serialize(),
                success: function(data) {
                    alert(data);
                    $('#add_name')[0].reset();
                }
            });
        });
    });
</script>





</x-backend.layouts.master>

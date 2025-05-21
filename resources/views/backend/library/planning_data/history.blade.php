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
                    <div class="card" style="overflow-x:auto;" id="hourlyProductionDashboardTable">
                        <div class="card-header">
                            <table class="table table-borderless table-hover">
                                <thead>
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
                            <table class="table table-bordered table-hover text-center">
                                <thead>
                                    <tr class="text-center">
                                        <th id="action_th">Action</th>
                                        <th>Line</th>
                                        <th>Buyer</th>
                                        <th>Style No.</th>
                                        <th>Item</th>
                                        <th>SMV</th>
                                        <th>Running M/C</th>
                                        <th>Operator</th>
                                        <th>Helpar</th>
                                        <th>Total M/P</th>
                                        <th>Working Hrs.</th>
                                        <th>Spent Minutes</th>
                                        <th>Production </th>
                                        <th>Output/ Day</th>
                                        <th>Produce minutes</th>
                                        <th>Efficiency. %</th>
                                        <th>TGT %</th>
                                        <th>TGT/ Hour</th>
                                        <th>TGT/ Day</th>
                                        <th>Performance</th>
                                        <th>Quality Check (Pcs)</th>
                                        <th>Defect Qty (Pcs)</th>
                                        <th>DHU %</th>
                                        <th>Remarks</th>
                                        <th>Bottlenecks M/C</th>
                                        <th>Bottlenecks Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($hpd_timeEntry as $key => $hourlyProductionDashboardTimeEntry)
                                        <tr>
                                            <td id="action_tr">
                                                <!--Edit button-->
                                                <a href="{{ route('time_entry_edit', $hourlyProductionDashboardTimeEntry->id) }}"
                                                    class="btn btn-outline-info btn-sm m-2">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form
                                                    action="{{ route('time_entry.destroy', $hourlyProductionDashboardTimeEntry->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure you want to delete this record?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>


                                            </td>
                                            <td>{{ $hourlyProductionDashboardTimeEntry->line }}</td>
                                            <td> {{ $hourlyProductionDashboardTimeEntry->buyer }}
                                            </td>
                                            <td>{{ $hourlyProductionDashboardTimeEntry->style }}
                                            </td>
                                            <td> {{ $hourlyProductionDashboardTimeEntry->item }}
                                            </td>
                                            <td> {{ number_format($hourlyProductionDashboardTimeEntry->smv, 2) }}
                                            </td>
                                            <td> {{ $hourlyProductionDashboardTimeEntry->running_machine }} </td>
                                            <td> {{ $hourlyProductionDashboardTimeEntry->operator }} </td>
                                            <td> {{ $hourlyProductionDashboardTimeEntry->helpar }} </td>
                                            <td>
                                                {{ $hourlyProductionDashboardTimeEntry->total_manpower }}
                                            </td>
                                            <td> {{ $hourlyProductionDashboardTimeEntry->working_hrs }} </td>
                                            <td> {{ $hourlyProductionDashboardTimeEntry->spent_minutes }}
                                            </td>
                                            <td> {{ $hourlyProductionDashboardTimeEntry->productions_start_hours }}
                                                {{-- -
                                                {{ $hourlyProductionDashboardTimeEntry->productions_end_hours }} --}}

                                            </td>
                                            <td>
                                                {{ $hourlyProductionDashboardTimeEntry->output_hour }}
                                            </td>
                                            <td>
                                                {{ $hourlyProductionDashboardTimeEntry->produce_minutes }}

                                            </td>
                                            <td>
                                                {{ number_format($hourlyProductionDashboardTimeEntry->efficiency, 2) }}
                                                %
                                            </td>
                                            <td>
                                                {{ $hourlyProductionDashboardTimeEntry->target }}

                                            </td>
                                            <td>
                                                {{ $hourlyProductionDashboardTimeEntry->target_per_hour }}
                                            </td>
                                            <td>
                                                {{ $hourlyProductionDashboardTimeEntry->target_per_day }}
                                            </td>
                                            <td>
                                                {{ number_format($hourlyProductionDashboardTimeEntry->performance, 2) ?? 0 }}
                                                % </td>


                                            <td>
                                                {{ $hourlyProductionDashboardTimeEntry->quality_check_pcs }}

                                            </td>
                                            <td>
                                                {{ $hourlyProductionDashboardTimeEntry->defect_qty_pcs }}

                                            </td>
                                            <td>
                                                {{ number_format($hourlyProductionDashboardTimeEntry->dhu, 2) }} %

                                            </td>
                                            <td>
                                                {{ $hourlyProductionDashboardTimeEntry->remarks }}
                                            </td>
                                            <td>{{ $hourlyProductionDashboardTimeEntry->Bottlenecks_machine }}
                                            </td>
                                            <td>{{ $hourlyProductionDashboardTimeEntry->Bottlenecks_remarks }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>





                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                    <div class="card-footer">
                        <a href="{{ route('hourlyProductionDashboards.index') }}" class="btn btn-outline-danger"><i
                                class="fas fa-backward"></i> Back</a>

                        <!-- print and send to email -->
                        <a class="btn btn-outline-primary" id="print_send_email"><i class="fas fa-envelope"></i>Email
                            Report</a>

                    </div>


                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <!-- Include SweetAlert 2 CSS and JS via CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $('#print_send_email').on('click', function() {
            var table = document.getElementById('hourlyProductionDashboardTable');
            // Get the HTML content of the table with border

            // Add border style to the table
            table.style.border = "1px solid black"; // Modify border style as needed

            // Get the outerHTML of the table
            var html = table.outerHTML;
            // Use SweetAlert input to get the email address
            Swal.fire({
                title: "Enter email address",
                input: "email",
                showCancelButton: true,
                confirmButtonText: "Send",
                cancelButtonText: "Cancel",
                inputValidator: (value) => {
                    // Validate if the entered value is a valid email address
                    return !value && 'You need to enter an email address!';
                }
            }).then((result) => {
                if (result.value) {
                    // Create CSRF token 
                    var csrf = document.createElement('meta');
                    csrf.name = 'csrf-token';
                    csrf.content = '{{ csrf_token() }}';
                    document.head.appendChild(csrf);

                    // Add CSRF token to the AJAX request
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    // Reference to the email button
                    var emailButton = $('#print_send_email');

                    // Hide the button
                    emailButton.hide();

                    // Send email
                    $.ajax({
                        url: '/send-email',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        data: {
                            html: html,
                            email: result.value
                        },
                        success: function(response) {
                            // Handle success response if needed
                            console.log('Email sent successfully');
                            // Show success message in sweet alert
                            Swal.fire('Email sent successfully', '', 'success');

                            // Show the button again after successful email sending
                            emailButton.show();
                        },
                        error: function(xhr, status, error) {
                            // Handle error response if needed
                            console.error('Error sending email:', error);
                            // Show error message in sweet alert
                            Swal.fire('Error sending email', '', 'error');
                            // Show the button again after failed email sending
                            emailButton.show();
                        }
                    });
                }
            });
        });
    </script>

    <script>
        // $('#print_send_email').on('click', function () {
        //     var table = document.getElementById('hourlyProductionDashboardTable');
        //     // Get the HTML content of the table with border and no padding from the table
        //     var tableHtml = $('#hourlyProductionDashboardTable').prop('outerHTML');

        //     var html = table.outerHTML;

        //     // Popup window for email address input
        //     var email = prompt('Please enter the email address to send the report', ' ');

        //     // Create CSRF token 
        //     var csrf = document.createElement('meta');
        //     csrf.name = 'csrf-token';
        //     csrf.content = '{{ csrf_token() }}';
        //     document.head.appendChild(csrf);

        //     // Add CSRF token to the AJAX request
        //     var csrfToken = $('meta[name="csrf-token"]').attr('content');

        //     // Reference to the email button
        //     var emailButton = $('#print_send_email');

        //     // Hide the button
        //     emailButton.hide();

        //     // Send email
        //     $.ajax({
        //         url: '/send-email',
        //         method: 'POST',
        //         headers: {
        //             'X-CSRF-TOKEN': csrfToken
        //         },
        //         data: {
        //             html: html,
        //             email: email
        //         },
        //         success: function (response) {
        //             // Handle success response if needed
        //             console.log('Email sent successfully');
        //             // Show the button again after successful email sending
        //             emailButton.show();
        //         },
        //         error: function (xhr, status, error) {
        //             // Handle error response if needed
        //             console.error('Error sending email:', error);
        //             // Show the button again after failed email sending
        //             emailButton.show();
        //         }
        //     });
        // });
    </script>

</x-backend.layouts.master>

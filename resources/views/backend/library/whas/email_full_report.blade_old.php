<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Hourly Production Dashboard Data Inforomation
    </x-slot>

    <x-slot name='breadCrumb'>
        <x-backend.layouts.elements.breadcrumb>
            <x-slot name="pageHeader"> Hourly Production Dashboard Data </x-slot>

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
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
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
                                        <!-- find the heighst working hours  and then create that number of columns for the output per hour -->
                                        @php
                                            $max_working_hrs = DB::table('hourly_production_dashboards')
                                                ->where('date', $hourlyProductionDashboard->date)
                                                ->where('floor', $hourlyProductionDashboard->floor)
                                                ->where('block', $hourlyProductionDashboard->block)
                                                ->max('working_hrs');

                                            $start_time = DB::table('hourly_production_dashboards')
                                                ->where('date', $hourlyProductionDashboard->date)
                                                ->where('floor', $hourlyProductionDashboard->floor)
                                                ->where('block', $hourlyProductionDashboard->block)
                                                ->min('start_time');
                                            // dd($max_working_hrs, $start_time);
                                        @endphp

                                        @for ($i = 1; $i <= $max_working_hrs; $i++)
                                            @php
                                                $time = \Carbon\Carbon::parse($start_time)->addHours($i);
                                            @endphp
                                            <th>{{ $time->format('h:i A') }}</th>
                                        @endfor

                                        <th>Production </th>
                                        <th>Output/ Day</th>
                                        <th>Produce minutes</th>
                                        <th>Efficiency. %</th>
                                        {{-- <th>TGT %</th> --}}
                                        <th>TGT/ Hour</th>
                                        <th>TGT/ Day</th>
                                        <th>Performance</th>
                                        <th>Quality Check (Pcs)</th>
                                        <th>Defect Qty (Pcs)</th>
                                        <th>DHU %</th>
                                        <th>Remarks</th>
                                        <th>Bottlenecks</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($hpd_timeEntry as $key => $hourlyProductionDashboardTimeEntry)
                                        <tr>
                                            @php
                                                $time_entry = DB::table('production_time_entries')
                                                    ->where(
                                                        'hourly_production_dashboard_id',
                                                        $hourlyProductionDashboardTimeEntry->id,
                                                    )
                                                    ->where('batch_id', $hourlyProductionDashboardTimeEntry->batch_id)
                                                    ->where(
                                                        'company_id',
                                                        $hourlyProductionDashboardTimeEntry->company_id,
                                                    )
                                                    ->where(
                                                        'division_id',
                                                        $hourlyProductionDashboardTimeEntry->division_id,
                                                    )
                                                    ->where('date', $hourlyProductionDashboardTimeEntry->date)
                                                    ->where('floor', $hourlyProductionDashboardTimeEntry->floor)
                                                    ->where('block', $hourlyProductionDashboardTimeEntry->block)
                                                    ->where('line', $hourlyProductionDashboardTimeEntry->line)
                                                    ->get();
                                                // dd($time_entry->count());
                                            @endphp
                                            @if ($time_entry->count() > 0)
                                                <td>{{ $hourlyProductionDashboardTimeEntry->line }}</td>
                                                <td> {{ $hourlyProductionDashboardTimeEntry->buyer }}
                                                </td>
                                                <td>{{ $hourlyProductionDashboardTimeEntry->style }}
                                                </td>
                                                <td> {{ $hourlyProductionDashboardTimeEntry->item }}
                                                </td>
                                                <td> {{ number_format($hourlyProductionDashboardTimeEntry->smv, 2) }}
                                                </td>
                                                <td>
                                                     {{-- {{ $hourlyProductionDashboardTimeEntry->running_machine }}  --}}
                                                     {{ $time_entry->sum('running_machine') }}
                                                    </td>
                                                {{-- <td> {{ $hourlyProductionDashboardTimeEntry->operator }} </td> --}}
                                                <td> {{ $time_entry->sum('operator') }} </td>
                                                <td> {{ $hourlyProductionDashboardTimeEntry->helpar }} </td>
                                                <td>
                                                    @php
                                                        $total_MP =
                                                            $hourlyProductionDashboardTimeEntry->operator +
                                                            $hourlyProductionDashboardTimeEntry->helpar;
                                                    @endphp
                                                    {{ $total_MP }}

                                                </td>
                                                <td> {{ $hourlyProductionDashboardTimeEntry->working_hrs }} </td>
                                                <td>
                                                    @if ($time_entry->count() > 0)
                                                        @php
                                                            $spent_minutes = $time_entry->sum('spent_minutes');
                                                            if ($spent_minutes > 0) {
                                                                $spent_minutes = $spent_minutes;
                                                            } else {
                                                                $spent_minutes = 0;
                                                            }
                                                        @endphp
                                                        {{ $spent_minutes }}
                                                    @else
                                                    @endif

                                                </td>
                                                <td>
                                                    @if ($time_entry->count() > 0)
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Time</th>
                                                                    <th>Achievements</th>

                                                                </tr>
                                                                @foreach ($time_entry as $item)
                                                                    <tr class="text-center">
                                                                        <td>

                                                                            {{ $item->productions_end_hours }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $item->output_hour }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                        </table>
                                                    @else
                                                        <table class="table table-bordered">
                                                            <tr class="text-center">
                                                                <span class="text-danger">No Data Found</span>
                                                            </tr>
                                                        </table>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($time_entry->count() > 0)
                                                        @php
                                                            $output_per_day = $time_entry->sum('output_hour');
                                                            if ($output_per_day > 0) {
                                                                $output_per_day = $output_per_day;
                                                            } else {
                                                                $output_per_day = 0;
                                                            }
                                                        @endphp
                                                        {{ $output_per_day ?? 0 }}
                                                    @else
                                                        0
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($time_entry->count() > 0)
                                                        @php
                                                            $Produce_minutes = ceil(
                                                                $output_per_day *
                                                                    number_format(
                                                                        $hourlyProductionDashboardTimeEntry->smv,
                                                                        2,
                                                                    ),
                                                            );
                                                            if ($Produce_minutes > 0) {
                                                                $Produce_minutes = $Produce_minutes;
                                                            } else {
                                                                $Produce_minutes = 0;
                                                            }
                                                        @endphp
                                                        {{ $Produce_minutes ?? 0 }}
                                                    @else
                                                        0
                                                    @endif

                                                </td>
                                                <td>
                                                    @if ($time_entry->count() > 0)
                                                        @php
                                                            $efficiency = ($Produce_minutes / $spent_minutes) * 100;
                                                            if ($efficiency > 0) {
                                                                $efficiency = $efficiency;
                                                            } else {
                                                                $efficiency = 0;
                                                            }
                                                        @endphp
                                                        {{ number_format($efficiency, 2) ?? 0 }} %
                                                    @else
                                                        0
                                                    @endif


                                                </td>
                                                {{-- <td>
                                                    @if ($time_entry->count() > 0)
                                                        @php
                                                            $tgt = $time_entry->avg('target');
                                                            if ($tgt > 0) {
                                                                $tgt = $tgt;
                                                            } else {
                                                                $tgt = 0;
                                                            }
                                                        @endphp
                                                        {{ ceil($tgt) ?? 0 }}
                                                    @else
                                                        0
                                                    @endif

                                                </td> --}}
                                                <td>
                                                    @if ($time_entry->count() > 0)
                                                        @php
                                                            $tgt_hour = ceil(
                                                                $hourlyProductionDashboardTimeEntry->target_per_day /
                                                                    $hourlyProductionDashboardTimeEntry->working_hrs,
                                                            );
                                                            if ($tgt_hour > 0) {
                                                                $tgt_hour = $tgt_hour;
                                                            } else {
                                                                $tgt_hour = 0;
                                                            }
                                                        @endphp
                                                        {{ $tgt_hour ?? 0 }}
                                                    @else
                                                        0
                                                    @endif

                                                </td>
                                                <td>
                                                    @if ($time_entry->count() > 0)
                                                        @php
                                                            $tgt_day =
                                                                $hourlyProductionDashboardTimeEntry->target_per_day;
                                                            if ($tgt_day > 0) {
                                                                $tgt_day = $tgt_day;
                                                            } else {
                                                                $tgt_day = 0;
                                                            }
                                                        @endphp
                                                        {{ $tgt_day ?? 0 }}
                                                    @else
                                                        0
                                                    @endif

                                                </td>
                                                <td>
                                                    @if ($time_entry->count() > 0)
                                                        @php
                                                            $performance =
                                                                ($time_entry->sum('output_hour') /
                                                                    $hourlyProductionDashboardTimeEntry->target_per_day) *
                                                                100;
                                                            if ($performance > 0) {
                                                                $performance = $performance;
                                                            } else {
                                                                $performance = 0;
                                                            }
                                                        @endphp
                                                        {{ number_format($performance, 2) ?? 0 }} %
                                                    @else
                                                        0
                                                    @endif

                                                </td>


                                                <td>
                                                    @if ($time_entry->count() > 0)
                                                        @php
                                                            $quality_check = $time_entry->sum('quality_check_pcs');
                                                            if ($quality_check > 0) {
                                                                $quality_check = $quality_check;
                                                            } else {
                                                                $quality_check = 0;
                                                            }
                                                        @endphp
                                                        {{ $quality_check ?? 0 }}
                                                    @else
                                                        0
                                                    @endif

                                                </td>
                                                <td>
                                                    @if ($time_entry->count() > 0)
                                                        @php
                                                            $defect_qty = $time_entry->sum('defect_qty_pcs');
                                                            if ($defect_qty > 0) {
                                                                $defect_qty = $defect_qty;
                                                            } else {
                                                                $defect_qty = 0;
                                                            }
                                                        @endphp
                                                        {{ $defect_qty ?? 0 }}
                                                    @else
                                                        0
                                                    @endif

                                                </td>
                                                <td>
                                                    @if ($time_entry->count() > 0)
                                                        @php
                                                            $dhu = ($defect_qty / $quality_check) * 100;
                                                            if ($dhu > 0) {
                                                                $dhu = $dhu;
                                                            } else {
                                                                $dhu = 0;
                                                            }
                                                        @endphp
                                                        {{ number_format($dhu, 2) ?? 0 }}
                                                    @else
                                                        0
                                                    @endif

                                                </td>
                                                <td>
                                                    {{ $hourlyProductionDashboardTimeEntry->remarks }}
                                                </td>
                                                <td>

                                                    @if ($time_entry->count() > 0)
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Time</th>
                                                                    <th>M/C</th>
                                                                    <th>Remarks</th>
                                                                </tr>
                                                                @foreach ($time_entry as $item)
                                                                    <tr class="text-center">
                                                                        <td>

                                                                            {{ $item->productions_end_hours }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $item->Bottlenecks_machine }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $item->Bottlenecks_remarks }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                        </table>
                                                    @endif
                                                </td>
                                            @else
                                                <td colspan="24" class="text-center">
                                                    <span class="text-danger">No Data Found</span>
                                                </td>
                                            @endif



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
                        <!--Print Report -->
                        <a class="btn btn-outline-info" id="print"><i class="fas fa-print"></i> Print</a>
                        <!--Export Report -->
                        <a class="btn btn-outline-success" id="export"><i class="fas fa-file-export"></i> Export</a>
                        <!-- print and send to email -->
                        <a class="btn btn-outline-primary" id="print_send_email"><i class="fas fa-envelope"></i> Print &
                            Send
                            to Email</a>
                    </div>


                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

        </div>
        <!-- /.container-fluid -->
        <?php
        $emailList = DB::table('email_lists')->pluck('email')->toArray();
        ?>
    </section>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <!-- Include SweetAlert 2 CSS and JS via CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#print').on('click', function() {
                var only = document.getElementById('hourlyProductionDashboardTable');
                var printContents = only.innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;

                // Apply CSS for landscape mode and A4 size paper with adjusted font size
                var style = document.createElement('style');
                style.innerHTML = '@page { size: A4 landscape; }';
                style.innerHTML += 'table { font-size: 10px; }'; // Adjust font size as needed
                document.head.appendChild(style);

                // Trigger print
                window.print();

                // Restore original content
                document.body.innerHTML = originalContents;
            });

            $('#export').on('click', function() {
                var table = document.getElementById('hourlyProductionDashboardTable');
                var html = table.outerHTML;
                var url = 'data:application/vnd.ms-excel,' + escape(html); // Set your html table into url 
                var a = document.createElement('a');
                a.href = url;
                a.download = 'hourlyProductionDashboard.xls'; // Your excel file name
                a.click();
            });


        });
    </script>

    <script>
        $('#print_send_email').on('click', function() {
            // Function to fetch email IDs from the users table
            function getEmailList() {
                <?php
                $emailList = DB::table('email_lists')->pluck('email')->toArray();
                ?>
                // Fetch email addresses from the database

                var emailList = <?php echo json_encode($emailList); ?>;
                console.log(emailList); // Debugging purpose

                return emailList;
            }

            // Get the HTML content of the table with border
            var table = document.getElementById('hourlyProductionDashboardTable');
            // Add border style to the table
            table.style.border = "1px solid black"; // Modify border style as needed
            // Get the outerHTML of the table
            var html = table.outerHTML;

            // Fetch the email list
            var emailList = getEmailList();

            // Create a modal with checkboxes for email selection
            Swal.fire({
                title: "Select email addresses",
                html: createCheckboxModal(emailList),
                showCancelButton: true,
                confirmButtonText: "Send",
                cancelButtonText: "Cancel",
                preConfirm: () => {
                    // Get the selected checkboxes
                    var selectedEmails = [];
                    $('input[name="emailCheckbox"]:checked').each(function() {
                        selectedEmails.push($(this).val());
                    });

                    return selectedEmails;
                },
                inputValidator: (value) => {
                    // Validate if at least one email address is selected
                    return value.length === 0 && 'You need to select at least one email address!';
                }
            }).then((result) => {
                if (result.value) {
                    // Call the main function to send email with the selected email addresses
                    sendEmail(html, result.value);
                }
            });
        });
        //  <input type="checkbox" id="newEmail" name="newEmail" value="newEmail"> New Email<br>
        // Function to create a modal with checkboxes for email selection
        function createCheckboxModal(emailList) {
            var modalHtml = `<div>
        <input type="checkbox" id="selectAllEmails" name="selectAllEmails" value="selectAllEmails"> Select All<br>
       `;

            emailList.forEach(function(email) {
                modalHtml +=
                    `<label><input type="checkbox" name="emailCheckbox" value="${email}"> ${email}</label><br>`;
            });
            modalHtml += '</div>';

            // Event listener for "Select All" checkbox
            $(document).on('change', '#selectAllEmails', function() {
                if ($(this).prop('checked')) {
                    $('input[name="emailCheckbox"]').prop('checked', true);
                } else {
                    $('input[name="emailCheckbox"]').prop('checked', false);
                }
            });

            // Event listener for "New Email" checkbox
            $(document).on('change', '#newEmail', function() {
                if ($(this).prop('checked')) {
                    Swal.fire({
                        title: "Enter new email address",
                        input: "email",
                        showCancelButton: true,
                        confirmButtonText: "Add",
                        cancelButtonText: "Cancel",
                        inputValidator: (newValue) => {
                            return !newValue && 'You need to enter an email address!';
                        }
                    }).then((newEmailResult) => {
                        if (newEmailResult.value) {
                            // Append the new email checkbox to the modal
                            modalHtml +=
                                `<label><input type="checkbox" name="emailCheckbox" value="${newEmailResult.value}" checked> ${newEmailResult.value}</label><br>`;
                            $('.swal2-content').html(modalHtml); // Update the modal content
                        } else {
                            $('#newEmail').prop('checked', false); // Uncheck "New Email" if canceled
                        }
                    });
                }
            });

            return modalHtml;
        }

        // Function to send email
        function sendEmail(html, emailList) {
            // Continue with the existing email sending logic 
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
                    emails: emailList // Send the email list as an array
                },
                success: function(response) {
                    // Handle success response if needed
                    console.log('Email sent successfully');
                    // Show success message in sweet alert
                    Swal.fire('Email sent successfully to ' + emailList.join(', '), '', 'success');

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

        // Function to check if the current time is 5 pm
        function isFivePM() {
            var now = new Date();
            return now.getHours() === 16 && now.getMinutes() === 16; // 17 is 5 pm
        }

        // Check if it's 5 pm and there is data in hourlyProductionDashboard
        if (isFivePM() && $('#hourlyProductionDashboardTable tr').length > 1) {
            // Get the HTML content of the table with border
            var table = document.getElementById('hourlyProductionDashboardTable');
            // Add border style to the table
            table.style.border = "1px solid black"; // Modify border style as needed
            // Get the outerHTML of the table
            var html = table.outerHTML;


            <?php
            $emailList = DB::table('email_lists')->where('status', 'active')->pluck('email')->toArray();
            ?>

            // Fetch the email list
            var emailList = <?php echo json_encode($emailList); ?>;

            // Continue with the existing email sending logic 
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

            // Check if there are emails to send
            if (emailList.length > 0) {
                // Send email
                $.ajax({
                    url: '/send-email',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        html: html,
                        emails: emailList // Send the email list as an array
                    },
                    success: function(response) {
                        // Handle success response if needed
                        console.log('Email sent successfully');
                        // Show success message in sweet alert
                        Swal.fire('Email sent successfully to ' + emailList.join(', '), '', 'success');
                    },
                    error: function(xhr, status, error) {
                        // Handle error response if needed
                        console.error('Error sending email:', error);
                        // Show error message in sweet alert
                        Swal.fire('Error sending email', '', 'error');
                    },
                    complete: function() {
                        // Show the button again after email sending (whether successful or not)
                        emailButton.show();
                    }
                });
            } else {
                console.log('No active email addresses available to send');
                // Show the button again if no active emails are available
                emailButton.show();
            }
        } else {
            console.log('Not 5 pm or no data in hourlyProductionDashboard');
        }
    </script>

    {{-- <script>
        $('#print_send_email').on('click', function() {
            // Function to fetch email IDs from the users table
            function getEmailList() {
                <?php
                $emailList = DB::table('users')->pluck('email')->toArray();
                ?>
                // Fetch email addresses from the database

                var emailList = <?php echo json_encode($emailList); ?>;
                console.log(emailList); // Debugging purpose

                return emailList;
            }

            // Get the HTML content of the table with border
            var table = document.getElementById('hourlyProductionDashboardTable');
            // Add border style to the table
            table.style.border = "1px solid black"; // Modify border style as needed
            // Get the outerHTML of the table
            var html = table.outerHTML;

            // Fetch the email list
            var emailList = getEmailList();

            // Use SweetAlert input to get the email addresses
            Swal.fire({
                title: "Select email addresses",
                input: "select",
                inputOptions: emailList.reduce((options, email) => {
                    options[email] = email;
                    return options;
                }, {
                    'add_new': 'Add New Email'
                }),
                showCancelButton: true,
                confirmButtonText: "Send",
                cancelButtonText: "Cancel",
                inputValidator: (value) => {
                    if (value === 'add_new') {
                        // If "Add New Email" is selected, prompt for a new email
                        Swal.fire({
                            title: "Enter new email address",
                            input: "email",
                            showCancelButton: true,
                            confirmButtonText: "Add",
                            cancelButtonText: "Cancel",
                            inputValidator: (newValue) => {
                                return !newValue && 'You need to enter an email address!';
                            }
                        }).then((newEmailResult) => {
                            if (newEmailResult.value) {
                                // Add the new email to the list
                                emailList.push(newEmailResult.value);
                                // Call the main function again with the updated email list
                                sendEmail(html, emailList);
                            }
                        });
                        return false;
                    }
                    // Validate if at least one email address is selected
                    return !value && 'You need to select at least one email address!';
                }
            }).then((result) => {
                if (result.value && result.value !== 'add_new') {
                    // Call the main function to send email with the selected email addresses
                    sendEmail(html, [result.value]);
                }
            });
        });

        // Function to send email
        function sendEmail(html, emailList) {
            // Continue with the existing email sending logic 
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
                    email: emailList // Send the email list as an array
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
    </script> --}}

    {{-- <script>
    $('#print_send_email').on('click', function () {
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
                    success: function (response) {
                        // Handle success response if needed
                        console.log('Email sent successfully');
                        // Show success message in sweet alert
                        Swal.fire('Email sent successfully', '', 'success');

                        // Show the button again after successful email sending
                        emailButton.show();
                    },
                    error: function (xhr, status, error) {
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
</script>   --}}

</x-backend.layouts.master>

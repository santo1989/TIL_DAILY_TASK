<x-backend.layouts.master>
    <x-slot name="pageTitle">Hourly Production Data Entry</x-slot>

    <style>
        .headerTable {
            width: 100%;
            border-collapse: collapse;
        }

        #headerTable th,
        #headerTable td {
            width: 12.5%;
        }
    </style>

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
                    <form action="{{ route('alterdata_store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="alter_id" value="{{ $planning_data->id }}">
                        <input type="hidden" name="start_time" value="{{ $planning_data->start_time }}">
                        <input type="hidden" name="end_time" value="{{ $planning_data->end_time }}">

                        <div class="card">
                            <div class="card-header">
                                <table class="table table-borderless table-hover" id="headerTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Date</th>
                                            <td><input type="text" class="form-control text-center" name="date"
                                                    value="{{ \Carbon\Carbon::parse($planning_data->date)->format('d-M-Y') }}"
                                                    readonly></td>

                                            <th class="text-center">Floor</th>
                                            <td><input type="text" class="form-control text-center" name="floor"
                                                    value="{{ $planning_data->floor }}{{ $planning_data->floor == 1 ? 'st' : ($planning_data->floor == 2 ? 'nd' : ($planning_data->floor == 3 ? 'rd' : 'th')) }} Floor"
                                                    readonly></td>

                                            <th class="text-center">Line</th>
                                            <td><input type="text" class="form-control text-center" name="line"
                                                    value="{{ $planning_data->line }}" readonly></td>
                                            <th class="text-center">Hour</th>
                                            <td>{{ $planning_data->start_time }} - {{ $planning_data->end_time }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Total Alter</th>
                                            <td><input type="number" class="form-control text-center"
                                                    name="total_alter" value="0" min="0" readonly></td>

                                            <th class="text-center">Total Production</th>
                                            <td><input type="number" class="form-control text-center"
                                                    name="total_production" value="0" min="0" required
                                                    readonly></td>

                                            <th class="text-center">Sewing DHU%</th>
                                            <td><input type="number" class="form-control text-center" name="sewing_dhu"
                                                    value="0" min="0" readonly></td>
                                            <th class="text-center">Total Check</th>
                                            <td><input type="number" class="form-control text-center"
                                                    name="total_check" value="0" min="0" readonly></td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Total Reject</th>
                                            <td><input type="number" class="form-control text-center"
                                                    name="total_reject" value="0" min="0" readonly></td>

                                            <th class="text-center">Total Reject %</th>
                                            <td><input type="text" class="form-control text-center"
                                                    name="total_reject_perent" value="0" min="0" required>
                                            </td>
                                            {{-- <th class="text-center"></th> --}}
                                            <th class="text-center"><button type="button"
                                                    class="btn btn-outline-success" id="alterEntryBtn">Alter
                                                    Entry</button></th>
                                            <th class="text-center"><button type="button"
                                                    class="btn btn-outline-danger" id="rejectEntryBtn">Reject
                                                    Entry</button></th>

                                            <th colspan="2" class="text-center">
                                                <!-- Button for counting total_production from this button click -->
                                                <button type="button" class="btn btn-outline-primary btn-lg"
                                                    id="totalProductionBtn">QC Pass</button>
                                            </th>

                                        </tr>
                                    </thead>
                                </table>

                                {{-- <!-- Toggle Buttons for Alter and Reject Entries -->
                                <div class="card-tools d-flex justify-content-center">
                                    <button type="button" class="btn btn-outline-success" id="alterEntryBtn">Alter Entry</button>
                                    <button type="button" class="btn btn-outline-danger" id="rejectEntryBtn">Reject Entry</button>
                                </div> --}}
                            </div>

                            <!-- Card Body for Input Fields -->
                            <div class="card-body">
                                <!-- Alter Entry Fields -->
                                <div class="alterEntryFields">
                                    @php
                                        $fields = [
                                            'Uneven_Shape',
                                            'Broken_Stitch',
                                            'Dirty_Mark',
                                            'Oil_Stain',
                                            'Down_Stitch',
                                            'Hiking',
                                            'Improper_Tuck',
                                            'Label_Alter',
                                            'Needle_Mark_Hole',
                                            'Open_Seam',
                                            'Skip_Stitch',
                                            'Pleat',
                                            'Sleeve_Shoulder_Up_Down',
                                            'Puckering',
                                            'Raw_Edge',
                                            'Shading',
                                            'Uncut_Thread',
                                            'Others',
                                        ];
                                        $fields = array_chunk($fields, 9);
                                    @endphp

                                    <div class="row">
                                        @foreach ($fields as $fieldChunk)
                                            <div class="col-md-6">
                                                @foreach ($fieldChunk as $field)
                                                    <div class="card mb-3 text-center bg-success text-white">
                                                        <div
                                                            class="d-flex justify-content-center align-items-center p-3">
                                                            <button class="btn btn-danger btn-lg decrement-btn"
                                                                type="button" style="width: 20%">-</button>
                                                            <span class="mx-2"
                                                                style="flex-grow: 1">{{ str_replace('_', ' ', $field) }}</span>
                                                            <input type="number" class="form-control text-center mx-2"
                                                                name="{{ Str::snake($field) }}" value="0"
                                                                min="0" readonly style="width: 20%">
                                                            <button class="btn btn-light btn-lg increment-btn"
                                                                type="button" style="width: 20%">+</button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Reject Entry Fields -->
                                <div class="rejectEntryFields" style="display: none;">
                                    @php
                                        $fields = [
                                            'Fabric_hole_Reject',
                                            'scissor_cuttar_cut_Reject',
                                            'Needle_hole_Reject',
                                            'Print_EMB_damage_Reject',
                                            'Shading_Reject',
                                            'Others_Reject',
                                        ];
                                        $fields = array_chunk($fields, 9);
                                    @endphp

                                    <div class="row">
                                        @foreach ($fields as $fieldChunk)
                                            <div class="col-md-6">
                                                @foreach ($fieldChunk as $field)
                                                    <div class="card mb-3 text-center bg-success text-white">
                                                        <div
                                                            class="d-flex justify-content-center align-items-center p-3">
                                                            <button class="btn btn-danger btn-lg decrement-btn"
                                                                type="button" style="width: 30%">-</button>
                                                            <span class="mx-2"
                                                                style="flex-grow: 1">{{ str_replace('_', ' ', $field) }}</span>
                                                            <input type="number"
                                                                class="form-control text-center mx-2"
                                                                name="{{ Str::snake($field) }}" value="0"
                                                                min="0" readonly>
                                                            <button class="btn btn-light btn-lg increment-btn"
                                                                type="button" style="width: 30%">+</button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Card Footer for Actions -->
                            <div class="card-footer">
                                <a href="{{ route('planning_data.index') }}" class="btn btn-outline-danger"
                                    id="backbutton"><i class="fas fa-backward"></i> Back</a>
                                <button type="reset" class="btn btn-outline-secondary"><i class="fas fa-undo"></i>
                                    Reset</button>
                                <button type="submit" class="btn btn-outline-success float-right"><i
                                        class="fas fa-save"></i> Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- JS for Dynamic Handling -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    {{-- <script>
        $(document).ready(function() {
            // Load stored values if available
            loadStoredValues();

            // Event listener for buttons to toggle between Alter and Reject entries
            $('#alterEntryBtn, #rejectEntryBtn').click(function() {
                let isAlter = this.id === 'alterEntryBtn';
                $('.alterEntryFields').toggle(isAlter);
                $('.rejectEntryFields').toggle(!isAlter);
            });

            // Generalized Increment/Decrement Button Functionality
            $('.increment-btn, .decrement-btn').click(function() {
                let input = $(this).siblings('input');
                let currentValue = parseInt(input.val()) || 0;
                let increment = $(this).hasClass('increment-btn') ? 1 : -1;
                if (increment === 1 || (increment === -1 && currentValue > 0)) {
                    input.val(currentValue + increment);
                }
                updateCalculations();
                saveToLocalStorage();
            });

            // Update calculations dynamically on direct input change
            $('input').on('input', function() {
                updateCalculations();
                saveToLocalStorage();
            });

            // Update calculations dynamically
            function updateCalculations() {
                // Update Total Production and other calculations based on values
                let totalProduction = parseInt($('input[name="total_production"]').val()) || 0;
                let totalAlter = calculateTotal('.alterEntryFields input');
                let totalReject = calculateTotal('.rejectEntryFields input');
                let sewingDHU = (totalAlter / totalProduction * 100).toFixed(2);
                let rejectPercentage = (totalReject / totalProduction * 100).toFixed(2);
                let totalCheck = totalProduction + totalAlter;

                $('input[name="total_alter"]').val(totalAlter);
                $('input[name="total_reject"]').val(totalReject);
                $('input[name="sewing_dhu"]').val(sewingDHU);
                $('input[name="total_reject_perent"]').val(rejectPercentage);
                $('input[name="total_check"]').val(totalCheck);
            }

            // Calculate total for a given set of inputs
            function calculateTotal(selector) {
                let total = 0;
                $(selector).each(function() {
                    total += parseInt($(this).val()) || 0;
                });
                return total;
            }

            // Save form state to local storage
            function saveToLocalStorage() {
                let formData = {};
                $('form').serializeArray().forEach(field => formData[field.name] = field.value);
                localStorage.setItem('formData', JSON.stringify(formData));
            }

            // Load stored form data from local storage
            function loadStoredValues() {
                let storedData = JSON.parse(localStorage.getItem('formData'));
                if (storedData) {
                    for (let key in storedData) {
                        $(`input[name="${key}"]`).val(storedData[key]);
                    }
                    updateCalculations();
                }
            }
        });

        // Reset local storage on form reset
        $('form').on('reset', function() {
            localStorage.removeItem('formData');
        });

        // Clear local storage on form submit
        $('form').on('submit', function() {
            localStorage.removeItem('formData');
        });

        //after submit button click hide all the button
        $('form').submit(function() {
            $('#alterEntryBtn').hide();
            $('#rejectEntryBtn').hide();
            $('.increment-btn').hide();
            $('.decrement-btn').hide();
            $('input').attr('readonly', true);
            $('button[type="reset"]').hide();
            $('button[type="submit"]').hide();
        });
    </script> --}}
    <script>
        $(document).ready(function() {
            // Load stored values if available
            loadStoredValues();

            // Event listener for the "QC Pass" button to increment total production
            $('#totalProductionBtn').click(function() {
                let totalProduction = parseInt($('input[name="total_production"]').val()) || 0;
                totalProduction += 1; // Increment total production by 1 on each button click
                $('input[name="total_production"]').val(totalProduction);

                updateCalculations();
                saveToLocalStorage();
            });
            // Event listener for buttons to toggle between Alter and Reject entries
            $('#alterEntryBtn, #rejectEntryBtn').click(function() {
                let isAlter = this.id === 'alterEntryBtn';
                $('.alterEntryFields').toggle(isAlter);
                $('.rejectEntryFields').toggle(!isAlter);
            });
            // Generalized Increment/Decrement Button Functionality for Alter/Reject entries
            $('.increment-btn, .decrement-btn').click(function() {
                let input = $(this).siblings('input');
                let currentValue = parseInt(input.val()) || 0;
                let increment = $(this).hasClass('increment-btn') ? 1 : -1;
                if (increment === 1 || (increment === -1 && currentValue > 0)) {
                    input.val(currentValue + increment);
                }
                updateCalculations();
                saveToLocalStorage();
            });

            // Update calculations dynamically on direct input change
            $('input').on('input', function() {
                updateCalculations();
                saveToLocalStorage();
            });

            // Function to update calculations dynamically
            function updateCalculations() {
                // Update Total Production and other calculations based on values
                let totalProduction = parseInt($('input[name="total_production"]').val()) || 0;
                let totalAlter = calculateTotal('.alterEntryFields input');
                let totalReject = calculateTotal('.rejectEntryFields input');
                let sewingDHU = (totalAlter / totalProduction * 100).toFixed(2);
                let rejectPercentage = (totalReject / totalProduction * 100).toFixed(2);
                let totalCheck = totalProduction + totalAlter;

                $('input[name="total_alter"]').val(totalAlter);
                $('input[name="total_reject"]').val(totalReject);
                $('input[name="sewing_dhu"]').val(sewingDHU);
                $('input[name="total_reject_perent"]').val(rejectPercentage);
                $('input[name="total_check"]').val(totalCheck);
            }

            // Function to calculate the total for a set of inputs
            function calculateTotal(selector) {
                let total = 0;
                $(selector).each(function() {
                    total += parseInt($(this).val()) || 0;
                });
                return total;
            }

            // Save form state to local storage
            function saveToLocalStorage() {
                let formData = {};
                $('form').serializeArray().forEach(field => formData[field.name] = field.value);
                localStorage.setItem('formData', JSON.stringify(formData));
            }

            // Load stored form data from local storage
            function loadStoredValues() {
                let storedData = JSON.parse(localStorage.getItem('formData'));
                if (storedData) {
                    for (let key in storedData) {
                        $(`input[name="${key}"]`).val(storedData[key]);
                    }
                    updateCalculations();
                }
            }

            // Reset local storage on form reset
            $('form').on('reset', function() {
                localStorage.removeItem('formData');
            });

            // Clear local storage on form submit
            $('form').on('submit', function() {
                localStorage.removeItem('formData');
            });

            // Hide all buttons after submitting the form
            $('form').submit(function() {
                $('#alterEntryBtn').hide();
                $('#rejectEntryBtn').hide();
                $('.increment-btn').hide();
                $('.decrement-btn').hide();
                $('input').attr('readonly', true);
                $('#totalProductionBtn').hide();
                $('#backbutton').hide();
                $('button[type="reset"]').hide();
                $('button[type="submit"]').hide();
            });
        });
    </script>

</x-backend.layouts.master>

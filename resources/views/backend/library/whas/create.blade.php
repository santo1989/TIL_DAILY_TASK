<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Create Hourly Production Dashboard Data
    </x-slot>

    <x-slot name='breadCrumb'>
        <x-backend.layouts.elements.breadcrumb>
            <x-slot name="pageHeader"> Hourly Production Dashboard Data </x-slot>
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('planning_data.index') }}">Hourly Production
                    Dashboard Data</a></li>
            <li class="breadcrumb-item active">Create Suppliers</li>
        </x-backend.layouts.elements.breadcrumb>
    </x-slot>


    <x-backend.layouts.elements.errors />
    <form action="{{ route('planning_data.store') }}" method="post" enctype="multipart/form-data" id="dynamicForm">
        <div>
            @csrf
            @php
                $division_id = auth::user()->division_id;
                $company_id = auth::user()->company_id;
            @endphp
            <input type="hidden" name="division_id" value="{{ $division_id }}">
            <input type="hidden" name="company_id" value="{{ $company_id }}">
            <div class="row">

                <div class="col-md-3 form-group">
                    <label for="floor">Floor</label>
                    <select name="floor" id="floor" class="form-control" required>
                        <option value="">Select Floor</option>
                        @foreach (range(1, 5) as $floor)
                            <option value="{{ $floor }}" {{ old('floor') == $floor ? 'selected' : '' }}>
                                {{ $floor }}{{ $floor === 1 ? 'st' : ($floor === 2 ? 'nd' : 'th') }} floor
                            </option>
                        @endforeach
                    </select>

                </div>

                <div class="col-md-2 form-group">
                    <label for="start_time">Start Time</label>
                    <input type="time" name="start_time" id="start_time" class="form-control" required
                        value="{{ old('start_time', now()->setTime(8, 0)->format('H:i')) }}">


                </div>

                <div class="col-md-2 form-group">
                    <label for="end_time">End Time</label>
                    <input type="time" name="end_time" id="end_time" class="form-control" required readonly
                        value="{{ old('end_time', now()->setTime(9, 0)->format('H:i')) }}">
                    <script>
                        //end_time = start_time + 1;
                        var start_time = document.getElementById('start_time');
                        var end_time = document.getElementById('end_time');
                        start_time.addEventListener('change', function() {
                            var start_time_value = start_time.value;
                            var start_time_hour = parseInt(start_time_value.split(':')[0]);
                            var start_time_minute = parseInt(start_time_value.split(':')[1]);
                            var end_time_hour = start_time_hour + 1;
                            var end_time_minute = start_time_minute;
                            if (end_time_hour < 10) {
                                end_time_hour = '0' + end_time_hour;
                            }
                            if (end_time_minute < 10) {
                                end_time_minute = '0' + end_time_minute;
                            }
                            end_time.value = end_time_hour + ':' + end_time_minute;
                        });
                    </script>



                </div>
                <div class="col-md-3 form-group">
                    <label for="section">Section</label>
                    <select name="section" id="section" class="form-control" required>
                        <option value="">Select Section</option>
                        <option value="sewing" {{ old('section') == 'sewing' ? 'selected' : '' }}>Sewing</option>
                        <option value="finishing" {{ old('section') == 'finishing' ? 'selected' : '' }}>Finishing
                        </option>
                    </select>
                </div>
                @php
                    $buyers = App\Models\Buyer::all();
                    $orders = DB::table('working_hour_allocations')->select('style')->distinct()->get();
                @endphp



                <div class="container">

                    <table class="table table-bordered" id="dynamicTable">
                        <thead>
                            <tr>
                                <th>Single Line</th>
                                <th>Marge Line</th>
                                <th>Buyer ID</th>
                                <th>Style</th>
                                <th>Item</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <!-- Single Line Column -->
                                <td>
                                    <div class="row form-group">
                                        @for ($i = 1; $i <= 20; $i++)
                                            <div class="col-md-2 col-sm-12">
                                                <div class="single-input">
                                                    <input class="single-input single-checkbox" type="checkbox"
                                                        value="{{ $i }}" id="line{{ $i }}"
                                                        name="single_line[]" data-id="{{ $i }}">
                                                    <label class="single-label"
                                                        for="line{{ $i }}">{{ $i }}</label>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                </td>
                                <!-- Marge Line Column -->
                                <td>
                                    <div class="row" id="lineCheckboxes">
                                        @for ($i = 1; $i <= 20; $i++)
                                            <div class="col-md-2 col-sm-12">
                                                <div class="form-check">
                                                    <input class="form-check-marge-input marge-checkbox" type="checkbox"
                                                        value="{{ $i }}"
                                                        id="margecheckboxline{{ $i }}"
                                                        name="margecheckboxline[]" data-id="{{ $i }}">
                                                    <label class="form-check-label"
                                                        for="margecheckboxline{{ $i }}">{{ $i }}</label>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                    <button type="button"
                                        class="btn btn-sm btn-outline-danger p-2 marge-add-btn">Add</button>
                                    <div class="row p-1 marge-line-show"></div>
                                </td>
                                <!-- Buyer Column -->
                                <td>
                                    <select name="buyer_id[]" class="form-control buyer-select" required>
                                        <option value="">Select Buyer</option>
                                        <option value="new_buyer"
                                            {{ old('buyer_id') == 'new_buyer' ? 'selected' : '' }}>Other</option>
                                        @foreach ($buyers as $buyer)
                                            <option value="{{ $buyer->id }}"
                                                {{ old('buyer_id') == $buyer->id ? 'selected' : '' }}>
                                                {{ $buyer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="new_buyer_name[]" class="form-control buyer-input"
                                        style="display:none;" placeholder="Enter Buyer Name"
                                        value="{{ old('new_buyer_name') }}">
                                </td>
                                <!-- Style Column -->
                                <td>
                                    <select name="style[]" class="form-control style-select" required>
                                        <option value="">Select Order / Style</option>
                                        <option value="new_style" {{ old('style') == 'new_style' ? 'selected' : '' }}>
                                            Other</option>
                                        @foreach ($orders as $order)
                                            <option value="{{ $order->style }}"
                                                {{ old('style') == $order->style ? 'selected' : '' }}>
                                                {{ $order->style }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="new_style_name[]" class="form-control style-input"
                                        style="display:none;" placeholder="Enter Style"
                                        value="{{ old('new_style_name') }}">
                                </td>
                                <!-- Item Column -->
                                <td>
                                    <select class="form-control item-select" name="item[]" required>
                                        <option value="">Select Item</option>
                                        <option value="other" {{ old('item') == 'other' ? 'selected' : '' }}>Other
                                        </option>
                                        <option value="hat" {{ old('item') == 'hat' ? 'selected' : '' }}>Hat
                                        </option>
                                        <option value="tanktop" {{ old('item') == 'tanktop' ? 'selected' : '' }}>
                                            Tanktop</option>
                                        <option value="racer" {{ old('item') == 'racer' ? 'selected' : '' }}>Racer
                                        </option>
                                        <option value="boxer" {{ old('item') == 'boxer' ? 'selected' : '' }}>Boxer
                                        </option>
                                        <option value="leggings" {{ old('item') == 'leggings' ? 'selected' : '' }}>
                                            Leggings</option>
                                        <option value="trouser" {{ old('item') == 'trouser' ? 'selected' : '' }}>
                                            Trouser</option>
                                        <option value="joggers" {{ old('item') == 'joggers' ? 'selected' : '' }}>
                                            Joggers</option>
                                        <option value="t-shirt" {{ old('item') == 't-shirt' ? 'selected' : '' }}>T
                                            Shirt</option>
                                        <option value="reversible-t-shirt"
                                            {{ old('item') == 'reversible-t-shirt' ? 'selected' : '' }}>Reversible T
                                            Shirt</option>
                                        <option value="reversible-pant"
                                            {{ old('item') == 'reversible-pant' ? 'selected' : '' }}>Reversible Pant
                                        </option>
                                        <option value="romper" {{ old('item') == 'romper' ? 'selected' : '' }}>Romper
                                        </option>
                                        <option value="sweat-shirt"
                                            {{ old('item') == 'sweat-shirt' ? 'selected' : '' }}>Sweat Shirt</option>
                                        <option value="polo-shirt"
                                            {{ old('item') == 'polo-shirt' ? 'selected' : '' }}>Polo Shirt</option>
                                        <option value="jacket" {{ old('item') == 'jacket' ? 'selected' : '' }}>Jacket
                                        </option>
                                        <option value="hoody-jacket"
                                            {{ old('item') == 'hoody-jacket' ? 'selected' : '' }}>Hoody Jacket</option>
                                        <option value="frocks" {{ old('item') == 'frocks' ? 'selected' : '' }}>Frocks
                                        </option>
                                        <option value="cargo-pant"
                                            {{ old('item') == 'cargo-pant' ? 'selected' : '' }}>Cargo Pant</option>
                                        <option value="blazer" {{ old('item') == 'blazer' ? 'selected' : '' }}>Blazer
                                        </option>
                                        <!-- Other options... -->
                                    </select>
                                    <input type="text" name="new_item_name[]" class="form-control item-input"
                                        style="display:none;" placeholder="Enter Item Name"
                                        value="{{ old('new_item_name') }}">
                                </td>
                                <!-- Action Column -->
                                <td>
                                    <button type="button" class="btn btn-primary copy-row">Copy</button>
                                    <button type="button" class="btn btn-danger remove-row">Remove</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="{{ route('planning_data.index') }}" class="btn btn-outline-danger"><i
                            class="fas fa-arrow-left"></i>
                        Back</a>
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-success submit-btn">Submit</button>


                </div>



            </div>




    </form>
    </div> <!-- End of Container -->



    {{-- <script>
        $(document).ready(function() {
            // Function to handle Copy Button Click
            $(document).on('click', '.copy-row', function() {
                var currentRow = $(this).closest('tr');
                var newRow = currentRow.clone();

                // Copy buyer, style, and item values to the new row
                newRow.find('.buyer-select').val(currentRow.find('.buyer-select').val());
                newRow.find('.style-select').val(currentRow.find('.style-select').val());
                newRow.find('.item-select').val(currentRow.find('.item-select').val());

                // Remove checkboxes and labels that have already been selected or are part of a group
                newRow.find('.single-checkbox, .marge-checkbox').each(function() {
                    var checkboxValue = $(this).val();
                    if ($(".single-checkbox[value='" + checkboxValue + "']").is(':disabled')) {
                        $(this).closest('div').remove(); // Remove checkbox and label
                    } else {
                        $(this).prop('checked', false).prop('disabled', false);
                    }
                });

                // Remove the marge line labels in copied rows
                newRow.find('.marge-line-show').empty();

                $('#dynamicTable tbody').append(newRow);
            });

            // Function to handle Remove Button Click
            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
            });

            // Show input fields when "Other" is selected
            $(document).on('change', '.buyer-select', function() {
                var inputField = $(this).closest('td').find('.buyer-input');
                if ($(this).val() === 'new_buyer') {
                    inputField.show();
                } else {
                    inputField.hide().val('');
                }
            });

            $(document).on('change', '.style-select', function() {
                var inputField = $(this).closest('td').find('.style-input');
                if ($(this).val() === 'new_style') {
                    inputField.show();
                } else {
                    inputField.hide().val('');
                }
            });

            $(document).on('change', '.item-select', function() {
                var inputField = $(this).closest('td').find('.item-input');
                if ($(this).val() === 'other') {
                    inputField.show();
                } else {
                    inputField.hide().val('');
                }
            });

            // Handle Marge Add Button Click
            $(document).on('click', '.marge-add-btn', function() {
                var checkedCheckboxes = $(this).closest('tr').find('.marge-checkbox:checked');
                if (checkedCheckboxes.length >= 2) {
                    var groupHtml = '<div class="col-3 card m-2 bg-info">';
                    groupHtml += '<div class="card-body justify-content-center">';

                    // Create a group ID to track this new group
                    var groupId = Date.now(); // Unique ID for each group
                    var groupLabel = [];

                    checkedCheckboxes.each(function() {
                        groupLabel.push($(this).val());
                        groupHtml += $(this).val() + (checkedCheckboxes.index(this) <
                            checkedCheckboxes.length - 1 ? ' + ' : '');

                        // Disable checkboxes in all rows that match the selected value
                        $(".single-checkbox[value='" + $(this).val() +
                            "'], .marge-checkbox[value='" + $(this).val() + "']").prop(
                            'disabled', true);
                    });

                    // Store group information for submission as concatenated string
                    var groupConcat = groupLabel.join(' + ');
                    groupHtml += '<input type="hidden" name="marge_group[' + groupId + ']" value="' +
                        groupConcat + '">';
                    groupHtml +=
                        '<br/><button type="button" class="btn btn-sm btn-outline-danger remove-btn">Remove</button>';
                    groupHtml += '</div></div>';

                    $(this).closest('tr').find('.marge-line-show').append(groupHtml);

                    // Disable selected checkboxes
                    checkedCheckboxes.prop('checked', false).prop('disabled', true);
                } else {
                    alert("Please select at least 2 checkboxes to create a group.");
                }
            });

            // Handle checkbox selections and disable logic
            $(document).on('change', '.single-checkbox, .marge-checkbox', function() {
                var value = $(this).val();
                var isChecked = $(this).is(":checked");

                // Disable corresponding checkboxes in all rows
                if (isChecked) {
                    $(".single-checkbox[value='" + value + "'], .marge-checkbox[value='" + value + "']")
                        .prop('disabled', true);
                } else {
                    $(".single-checkbox[value='" + value + "'], .marge-checkbox[value='" + value + "']")
                        .prop('disabled', false);
                }
            });

            // Handle Remove Button Click for Marge Groups
            $(document).on('click', '.remove-btn', function() {
                var groupElement = $(this).closest('.card');
                groupElement.find('input').each(function() {
                    var lineValue = $(this).val();
                    $(".single-checkbox[value='" + lineValue + "'], .marge-checkbox[value='" +
                        lineValue + "']").prop('disabled', false);
                });
                groupElement.remove();
            });

            // Handle Form Submission
            $(document).on('click', '.submit-btn', function() {
                var rowsData = [];

                $('#dynamicTable tbody tr').each(function() {
                    var $row = $(this);
                    var buyerId = $row.find('.buyer-select').val();
                    var style = $row.find('.style-select').val();
                    var item = $row.find('.item-select').val();

                    // Check for new buyer, style, item and pick input values accordingly
                    if (buyerId === 'new_buyer') {
                        buyerId = $row.find('.buyer-input').val();
                    }

                    if (style === 'new_style') {
                        style = $row.find('.style-input').val();
                    }

                    if (item === 'other') {
                        item = $row.find('.item-input').val();
                    }

                    var lineData = [];

                    // Collect line data for single checkbox
                    $row.find('.single-checkbox:checked').each(function() {
                        lineData.push($(this).val());
                    });

                    // Collect group data
                    $row.find('.marge-line-show .card-body').each(function() {
                        lineData.push($(this).find('input').val());
                    });

                    // Prepare row data
                    var rowObject = {
                        line: lineData,
                        buyer_id: Array(lineData.length).fill(buyerId),
                        style: Array(lineData.length).fill(style),
                        item: Array(lineData.length).fill(item)
                    };

                    rowsData.push(rowObject);
                });

                // Process collected data for submission
                var buyerResults = [];
                var styleResults = [];
                var itemResults = [];

                rowsData.forEach(function(row) {
                    row.line.forEach(function(line, index) {
                        // Push line data
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'line[]',
                            value: line
                        }).appendTo('form');

                        // Collect buyer, style, item data for submission
                        buyerResults.push(row.buyer_id[index]);
                        styleResults.push(row.style[index]);
                        itemResults.push(row.item[index]);
                    });
                });

                // Unique values for each entry
                var uniqueBuyerIds = [...new Set(buyerResults)];
                var uniqueStyles = [...new Set(styleResults)];
                var uniqueItems = [...new Set(itemResults)];

                // Append unique entries to form
                uniqueBuyerIds.forEach(function(buyer) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'buyer_id[]',
                        value: buyer
                    }).appendTo('form');
                });

                uniqueStyles.forEach(function(style) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'style[]',
                        value: style
                    }).appendTo('form');
                });

                uniqueItems.forEach(function(item) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'item[]',
                        value: item
                    }).appendTo('form');
                });

                // Submit the form
                $('form').submit();
            });
        });

        // after click submit button hide the submit button
        $(document).on('click', '.submit-btn', function() {
            $(this).hide();
        });
    </script> --}}

    <script>
        $(document).ready(function() {
            // Function to handle Copy Button Click
            $(document).on('click', '.copy-row', function() {
                var currentRow = $(this).closest('tr');
                var newRow = currentRow.clone();

                // Copy buyer, style, and item values to the new row
                newRow.find('.buyer-select').val(currentRow.find('.buyer-select').val());
                newRow.find('.style-select').val(currentRow.find('.style-select').val());
                newRow.find('.item-select').val(currentRow.find('.item-select').val());

                // Remove checkboxes and labels that have already been selected or are part of a group
                newRow.find('.single-checkbox, .marge-checkbox').each(function() {
                    var checkboxValue = $(this).val();
                    if ($(".single-checkbox[value='" + checkboxValue + "']").is(':disabled')) {
                        $(this).closest('div').remove(); // Remove checkbox and label
                    } else {
                        $(this).prop('checked', false).prop('disabled', false);
                    }
                });

                // Remove the marge line labels in copied rows
                newRow.find('.marge-line-show').empty();

                $('#dynamicTable tbody').append(newRow);
            });

            // Function to handle Remove Button Click
            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
            });

            // Show input fields when "Other" is selected
            $(document).on('change', '.buyer-select', function() {
                var inputField = $(this).closest('td').find('.buyer-input');
                if ($(this).val() === 'new_buyer') {
                    inputField.show();
                } else {
                    inputField.hide().val('');
                }
            });

            $(document).on('change', '.style-select', function() {
                var inputField = $(this).closest('td').find('.style-input');
                if ($(this).val() === 'new_style') {
                    inputField.show();
                } else {
                    inputField.hide().val('');
                }
            });

            $(document).on('change', '.item-select', function() {
                var inputField = $(this).closest('td').find('.item-input');
                if ($(this).val() === 'other') {
                    inputField.show();
                } else {
                    inputField.hide().val('');
                }
            });

            // Handle Marge Add Button Click
            $(document).on('click', '.marge-add-btn', function() {
                var checkedCheckboxes = $(this).closest('tr').find('.marge-checkbox:checked');
                if (checkedCheckboxes.length >= 2) {
                    var groupHtml = '<div class="col-3 card m-2 bg-info">';
                    groupHtml += '<div class="card-body justify-content-center">';

                    // Create a group ID to track this new group
                    var groupId = Date.now(); // Unique ID for each group
                    var groupLabel = [];

                    checkedCheckboxes.each(function() {
                        groupLabel.push($(this).val());
                        groupHtml += $(this).val() + (checkedCheckboxes.index(this) <
                            checkedCheckboxes.length - 1 ? ' + ' : '');

                        // Disable checkboxes in all rows that match the selected value
                        $(".single-checkbox[value='" + $(this).val() +
                            "'], .marge-checkbox[value='" + $(this).val() + "']").prop(
                            'disabled', true);
                    });

                    // Store group information for submission as concatenated string
                    var groupConcat = groupLabel.join(' + ');
                    groupHtml += '<input type="hidden" name="marge_group[' + groupId + ']" value="' +
                        groupConcat + '">';
                    groupHtml +=
                        '<br/><button type="button" class="btn btn-sm btn-outline-danger remove-btn">Remove</button>';
                    groupHtml += '</div></div>';

                    $(this).closest('tr').find('.marge-line-show').append(groupHtml);

                    // Disable selected checkboxes
                    checkedCheckboxes.prop('checked', false).prop('disabled', true);
                } else {
                    alert("Please select at least 2 checkboxes to create a group.");
                }
            });

            // Handle checkbox selections and disable logic
            $(document).on('change', '.single-checkbox, .marge-checkbox', function() {
                var value = $(this).val();
                var isChecked = $(this).is(":checked");

                // Disable corresponding checkboxes in all rows
                if (isChecked) {
                    $(".single-checkbox[value='" + value + "'], .marge-checkbox[value='" + value + "']")
                        .prop('disabled', true);
                } else {
                    $(".single-checkbox[value='" + value + "'], .marge-checkbox[value='" + value + "']")
                        .prop('disabled', false);
                }
            });

            // Handle Remove Button Click for Marge Groups
            $(document).on('click', '.remove-btn', function() {
                var groupElement = $(this).closest('.card');
                groupElement.find('input').each(function() {
                    var lineValue = $(this).val();
                    $(".single-checkbox[value='" + lineValue + "'], .marge-checkbox[value='" +
                        lineValue + "']").prop('disabled', false);
                });
                groupElement.remove();
            });

            // Handle Form Submission
            $(document).on('click', '.submit-btn', function() {

                var rowsData = [];
                var isValid = true; // Flag to track validation status

                $('#dynamicTable tbody tr').each(function() {
                    var $row = $(this);
                    var buyerId = $row.find('.buyer-select').val();
                    var style = $row.find('.style-select').val();
                    var item = $row.find('.item-select').val();

                    // Check for new buyer, style, item and pick input values accordingly
                    if (buyerId === 'new_buyer') {
                        buyerId = $row.find('.buyer-input').val();
                    }

                    if (style === 'new_style') {
                        style = $row.find('.style-input').val();
                    }

                    if (item === 'other') {
                        item = $row.find('.item-input').val();
                    }

                    var lineData = [];

                    // Collect line data for single checkbox
                    var singleCheckboxSelected = false;
                    $row.find('.single-checkbox:checked').each(function() {
                        lineData.push($(this).val());
                        singleCheckboxSelected =
                        true; // Mark that at least one single checkbox is selected
                    });

                    // Collect group data
                    var margeGroupSelected = false;
                    $row.find('.marge-line-show .card-body').each(function() {
                        lineData.push($(this).find('input').val());
                        margeGroupSelected =
                        true; // Mark that at least one marge line group is selected
                    });

                    // Ensure at least one checkbox is selected
                    if (!singleCheckboxSelected && !margeGroupSelected) {
                        isValid = false; // Set flag to false if no checkboxes are selected
                    }

                    // Prepare row data
                    var rowObject = {
                        line: lineData,
                        buyer_id: Array(lineData.length).fill(buyerId),
                        style: Array(lineData.length).fill(style),
                        item: Array(lineData.length).fill(item)
                    };

                    rowsData.push(rowObject);
                });

                // Show an alert if validation fails
                if (!isValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please fill out all required fields and select at least one checkbox per row before submitting.'
                    });
                    return false;
                }

                // Process collected data for submission
                var buyerResults = [];
                var styleResults = [];
                var itemResults = [];

                rowsData.forEach(function(row) {
                    row.line.forEach(function(line, index) {
                        // Ensure no empty or invalid values are pushed
                        if (line) {
                            $('<input>').attr({
                                type: 'hidden',
                                name: 'line[]',
                                value: line
                            }).appendTo('form');

                            // Collect buyer, style, item data for submission
                            buyerResults.push(row.buyer_id[index]);
                            styleResults.push(row.style[index]);
                            itemResults.push(row.item[index]);
                        }
                    });
                });

                // Check if all results arrays are non-empty before appending
                if (buyerResults.length > 0) {
                    var uniqueBuyerIds = [...new Set(buyerResults)];
                    uniqueBuyerIds.forEach(function(buyer) {
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'buyer_id[]',
                            value: buyer
                        }).appendTo('form');
                    });
                }

                if (styleResults.length > 0) {
                    var uniqueStyles = [...new Set(styleResults)];
                    uniqueStyles.forEach(function(style) {
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'style[]',
                            value: style
                        }).appendTo('form');
                    });
                }

                if (itemResults.length > 0) {
                    var uniqueItems = [...new Set(itemResults)];
                    uniqueItems.forEach(function(item) {
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'item[]',
                            value: item
                        }).appendTo('form');
                    });
                }

                // Submit the form
                $('form').submit();
                $('.copy-row').hide();
                $('.remove-row').hide();
                $('.marge-add-btn').hide();
                $('.remove-btn').hide();
                $(this).hide();
            });
        });
    </script>









</x-backend.layouts.master>

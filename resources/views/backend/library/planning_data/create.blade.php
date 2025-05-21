<x-backend.layouts.master>
    <x-slot name="pageTitle">
        Create Planning Data
    </x-slot>

    <x-slot name='breadCrumb'>
        <x-backend.layouts.elements.breadcrumb>
            <x-slot name="pageHeader"> Planning Data </x-slot>
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('planning_data.index') }}">Hourly Production
                    Dashboard Data</a></li>
            <li class="breadcrumb-item active">Create Suppliers</li>
        </x-backend.layouts.elements.breadcrumb>
    </x-slot>


    <x-backend.layouts.elements.errors />
    <form action="{{ route('planning_data_store') }}" method="post" enctype="multipart/form-data" id="dynamicForm">
        <div>
            @csrf
            @php
                $division_id = auth::user()->division_id;
                $company_id = auth::user()->company_id;
            @endphp
            <input type="hidden" name="division_id" value="{{ $division_id }}">
            <input type="hidden" name="company_id" value="{{ $company_id }}">

            @php
                $buyers = App\Models\Buyer::all();
                $orders = DB::table('planning_data')->select('style')->distinct()->get();
                $items = DB::table('planning_data')->select('item')->distinct()->get();
            @endphp



            <div class="container-fluid">

                <table class="table table-bordered table-hover" id="dynamicTable">
                    <thead>
                        <tr>
                            <th>Floor</th>
                            <th>Line</th>
                            <th>Buyer</th>
                            <th>Style</th>
                            <th>Item</th>
                            <th>smv</th>
                            <th>Order Qty</th>
                            <th>Allocate Qty</th>
                            <th>Sewing Start</th>
                            <th>Sewing End</th>
                            <th>Required Man Power</th>
                            <th>Average Working Hour</th>
                            <th>Expected Efficiency</th>
                            <th>Remarks</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>

                                <select name="floor[]" id="floor" class="form-control" required>
                                    <option value="">Select Floor</option>
                                    @foreach (range(1, 5) as $floor)
                                        <option value="{{ $floor }}"
                                            {{ old('floor') == $floor ? 'selected' : '' }}>
                                            {{ $floor }}{{ $floor === 1 ? 'st' : ($floor === 2 ? 'nd' : 'th') }}
                                            floor
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" name="line[]" class="form-control" required
                                    value="{{ old('line') }}">

                            </td>
                            <td>
                                <select name="buyer_id[]" class="form-control buyer-select" required>
                                    <option value="">Select Buyer</option>
                                    <option value="new_buyer" {{ old('buyer_id') == 'new_buyer' ? 'selected' : '' }}>
                                        Other</option>
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
                                    <option value="sweat-shirt" {{ old('item') == 'sweat-shirt' ? 'selected' : '' }}>
                                        Sweat Shirt</option>
                                    <option value="polo-shirt" {{ old('item') == 'polo-shirt' ? 'selected' : '' }}>Polo
                                        Shirt</option>
                                    <option value="jacket" {{ old('item') == 'jacket' ? 'selected' : '' }}>Jacket
                                    </option>
                                    <option value="hoody-jacket" {{ old('item') == 'hoody-jacket' ? 'selected' : '' }}>
                                        Hoody Jacket</option>
                                    <option value="frocks" {{ old('item') == 'frocks' ? 'selected' : '' }}>Frocks
                                    </option>
                                    <option value="cargo-pant" {{ old('item') == 'cargo-pant' ? 'selected' : '' }}>
                                        Cargo Pant</option>
                                    <option value="blazer" {{ old('item') == 'blazer' ? 'selected' : '' }}>Blazer
                                    </option>
                                    <!-- Other options for Item array from database -->
                                    @foreach ($items as $item)
                                        <option value="{{ $item->item }}"
                                            {{ old('item') == $item->item ? 'selected' : '' }}>
                                            {{ $item->item }}
                                        </option>
                                    @endforeach

                                </select>
                                <input type="text" name="new_item_name[]" class="form-control item-input"
                                    style="display:none;" placeholder="Enter Item Name"
                                    value="{{ old('new_item_name') }}">
                            </td>
                            <!-- Order Qty Column -->
                            <td>
                                <input type="number" name="smv[]" class="form-control" required
                                    value="{{ old('smv') }}" step="0.01">
                            </td>
                            <td>
                                <input type="number" name="order_qty[]" class="form-control" required
                                    value="{{ old('order_qty') }}">
                            </td>
                            <!-- Allocate Qty Column -->
                            <td>
                                <input type="number" name="allocate_qty[]" class="form-control" required
                                    value="{{ old('allocate_qty') }}">
                            </td>
                            <!-- Sewing Start Column -->
                            <td>
                                <input type="date" name="sewing_start[]" class="form-control" required
                                    value="{{ old('sewing_start') }}">
                            </td>
                            <!-- Sewing End Column -->
                            <td>
                                <input type="date" name="sewing_end[]" class="form-control" required
                                    value="{{ old('sewing_end') }}">
                            </td>
                            <!-- Required Man Power Column -->
                            <td>
                                <input type="number" name="required_man_power[]" class="form-control" 
                                    value="{{ old('required_man_power') }}">
                            </td>
                            <!-- Average Working Hour Column -->
                            <td>
                                <input type="number" name="average_working_hour[]" class="form-control" 
                                    value="{{ old('average_working_hour') }}">
                            </td>
                            <!-- Expected Efficiency Column -->
                            <td>
                                <input type="number" name="expected_efficiency[]" class="form-control" 
                                    value="{{ old('expected_efficiency') }}">
                            </td>
                            <!-- Remarks Column -->
                            <td>
                                <input type="text" name="remarks[]" class="form-control" 
                                    value="{{ old('remarks') }}">
                            </td>


                            <!-- Action Column -->
                            <td>
                                <button type="button" class="btn btn-primary copy-row">Copy</button>
                                <!-- Add Copy All Data Button -->
                                <button type="button" class="btn btn-info copy-all-rows">Copy All Data</button>

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



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tableBody = document.querySelector("#dynamicTable tbody");

            function clearRowInputs(row) {
                row.querySelectorAll("input").forEach(input => input.value = "");
                row.querySelectorAll("select").forEach(select => select.selectedIndex = 0);
            }

            function toggleOtherField(select, inputField) {
                if (select.value === "new_buyer" || select.value === "new_style" || select.value === "other") {
                    inputField.style.display = "block";
                    inputField.required = true;
                } else {
                    inputField.style.display = "none";
                    inputField.required = false;
                }
            }

            // Handle Copy and Remove Row Buttons
            tableBody.addEventListener("click", function(event) {
                const currentRow = event.target.closest("tr");

                if (event.target.classList.contains("copy-row")) {
                    // Clone the row
                    const newRow = currentRow.cloneNode(true);

                    // Copy selected data for floor, buyer, style, item, etc.
                    const floorSelect = currentRow.querySelector("select[name='floor']");
                    const buyerSelect = currentRow.querySelector("select[name='buyer_id[]']");
                    const styleSelect = currentRow.querySelector("select[name='style[]']");
                    const itemSelect = currentRow.querySelector("select[name='item[]']");

                    const newFloorSelect = newRow.querySelector("select[name='floor']");
                    const newBuyerSelect = newRow.querySelector("select[name='buyer_id[]']");
                    const newStyleSelect = newRow.querySelector("select[name='style[]']");
                    const newItemSelect = newRow.querySelector("select[name='item[]']");

                    // Copy the selected values from the original row to the new row
                    newFloorSelect.value = floorSelect.value;
                    newBuyerSelect.value = buyerSelect.value;
                    newStyleSelect.value = styleSelect.value;
                    newItemSelect.value = itemSelect.value;

                    // If "Other" was selected in Buyer, Style, or Item, handle the input field visibility
                    toggleOtherField(buyerSelect, newRow.querySelector("input[name='new_buyer_name[]']"));
                    toggleOtherField(styleSelect, newRow.querySelector("input[name='new_style_name[]']"));
                    toggleOtherField(itemSelect, newRow.querySelector("input[name='new_item_name[]']"));

                    // Clear other inputs in the new row
                    clearRowInputs(newRow);

                    tableBody.appendChild(newRow);
                }

                if (event.target.classList.contains("remove-row")) {
                    if (tableBody.querySelectorAll("tr").length > 1) {
                        currentRow.remove();
                    } else {
                        alert("You need at least one row!");
                    }
                }
            });

            // Handle Copy All Data Button
            document.querySelector(".copy-all-rows").addEventListener("click", function() {
                const allRows = Array.from(tableBody.querySelectorAll("tr"));
                allRows.forEach(row => {
                    const newRow = row.cloneNode(true);
                    tableBody.appendChild(newRow);
                });
            });

            // Handle Select Fields for Other Options
            tableBody.addEventListener("change", function(event) {
                if (event.target.matches(".buyer-select, .style-select, .item-select")) {
                    const select = event.target;
                    const inputField = select.closest("td").querySelector("input");
                    toggleOtherField(select, inputField);
                }
            });

            //before submit form check htmlspecialchars and remove extra spaces
            document.querySelector(".submit-btn").addEventListener("click", function() {
                const allInputs = tableBody.querySelectorAll("input, select");
                allInputs.forEach(input => {
                    input.value = input.value.trim();
                });
            });

            // Form Validation on Submit
            document.querySelector(".submit-btn").addEventListener("click", function(event) {
                let isValid = true;

                tableBody.querySelectorAll("tr").forEach(row => {
                    row.querySelectorAll("input, select").forEach(input => {
                        if (!input.checkValidity()) {
                            isValid = false;
                            input.classList.add("is-invalid");
                        } else {
                            input.classList.remove("is-invalid");
                        }
                    });
                });

                if (!isValid) {
                    event.preventDefault();
                    alert("Please fix the errors in the form before submitting.");
                }
            });
        });
    </script>









</x-backend.layouts.master>

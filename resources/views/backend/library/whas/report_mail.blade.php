 <x-backend.layouts.master>

     <div class="container-fluid">
         <div class="row justify-content-between">
             <div class="col-md-2"><a href="{{ route('planning_data.index') }}" class="btn btn-outline-secondary my-3"><i
                         class="bi bi-arrow-left"></i> Back</a>
                 <!--download xls Button-->

                 <button onclick="downloadExcelFile()" class="btn btn-outline-success my-3"><i
                         class="bi bi-file-earmark-excel"></i> Download Excel</button>

             </div>
             <div class="col-md-10">
                 <h1 class="text-center">Hourly Production Alter and Reject Report</h1>
             </div>

         </div>

         <div class="card" style="overflow-x:auto;" id="ProductionDataTable">

             <!-- card-body -->
             <div class="card-body">
                 <!-- Display information for the current row -->
                 <table class="table table-bordered table-striped table-hover text-nowra text-center">
                     <thead class="thead-dark">
                         <tr>
                             <th rowspan="2">Date</th>
                             <th rowspan="2">Floor</th>
                             <th rowspan="2">Buyer</th>
                             <th rowspan="2">Style</th>
                             <th rowspan="2">Item</th>
                             <th rowspan="2">Line</th>
                             <th colspan="22">Alter Data</th>
                             <th colspan="9">Reject
                                 Data</th>
                         </tr>
                         <tr class="text-center">

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
                             <!-- Reject Data start -->
                             <th>Hours</th>
                             <th>Reject %</th>
                             <th>Total Reject</th>
                             <th>Fabric_hole </th>
                             <th>Scissor/ Cuttar Cut</th>
                             <th>Needle Hole</th>
                             <th>Print/ EMB Damage</th>
                             <th>Shading</th>
                             <th>Others</th>
                             <!-- Reject Data end -->
                         </tr>
                     </thead>
                     <tbody>
                         @foreach ($planning_data as $wha)
                             <tr>
                                 <td>{{ \Carbon\Carbon::parse($wha->date)->format('d-M-Y') }}
                                 </td>
                                 <td>{{ $wha->floor }}{{ $wha->floor == 1 ? 'st' : ($wha->floor == 2 ? 'nd' : ($wha->floor == 3 ? 'rd' : 'th')) }}
                                     Floor</td>
                                 <td>{{ $wha->buyer }}
                                 </td>
                                 <td>{{ $wha->style }}
                                 </td>
                                 <td>{{ $wha->item }}
                                 </td>
                                 <td>{{ $wha->line }}
                                 </td>

                                 <td>{{ $wha->start_time }}
                                     -
                                     {{ $wha->end_time }}
                                 </td>
                                 <td>
                                     @php
                                         $sewing_dhu = $wha->sewing_dhu;
                                         // show only 2 decimal points
                                         $sewing_dhu = number_format($sewing_dhu, 2);
                                         echo $sewing_dhu;
                                     @endphp
                                 </td>
                                 <td>{{ $wha->Total_Production }}
                                 </td>
                                 <td>{{ $wha->Total_Alter }}
                                 </td>
                                 <td>{{ $wha->total_check }}
                                 </td>
                                 <td>{{ $wha->Uneven_Shape }}
                                 </td>
                                 <td>{{ $wha->Broken_Stitch }}
                                 </td>
                                 <td>{{ $wha->Dirty_Mark }}
                                 </td>
                                 <td>{{ $wha->Oil_Stain }}
                                 </td>
                                 <td>{{ $wha->Down_Stitch }}
                                 </td>
                                 <td>{{ $wha->Hiking }}
                                 </td>
                                 <td>{{ $wha->Improper_Tuck }}
                                 </td>
                                 <td>{{ $wha->Label_Alter }}
                                 </td>
                                 <td>{{ $wha->Needle_Mark_Hole }}
                                 </td>
                                 <td>{{ $wha->Open_Seam }}
                                 </td>
                                 <td>{{ $wha->Skip_Stitch }}
                                 </td>
                                 <td>{{ $wha->Pleat }}
                                 </td>
                                 <td>{{ $wha->Sleeve_Shoulder_Up_Down }}
                                 </td>
                                 <td>{{ $wha->Puckering }}
                                 </td>
                                 <td>{{ $wha->Raw_Edge }}
                                 </td>
                                 <td>{{ $wha->Shading }}
                                 </td>
                                 <td>{{ $wha->Others }}
                                 </td>
                                 <td>{{ $wha->start_time }}
                                     -
                                     {{ $wha->end_time }}
                                 </td>
                                 <td>
                                     @php
                                         $reject_dhu = $wha->reject_dhu;
                                         // show only 2 decimal points
                                         $reject_dhu = number_format($reject_dhu, 2);
                                         echo $reject_dhu;
                                     @endphp
                                 </td>
                                 <td>{{ $wha->Total_reject }}
                                 </td>
                                 <td>{{ $wha->reject_Fabric_hole }}
                                 </td>
                                 <td>{{ $wha->reject_scissor_cuttar_cut }}
                                 </td>
                                 <td>{{ $wha->reject_Needle_hole }}
                                 </td>
                                 <td>{{ $wha->reject_Print_emb_damage }}
                                 </td>
                                 <td>{{ $wha->reject_Shading }}
                                 </td>
                                 <td>{{ $wha->reject_Others }}

                             </tr>
                         @endforeach
                         <tr>
                             <td colspan='7'>
                                 <strong>Total</strong>
                             </td>
                             <td><strong>
                                     @php
                                         $sewing_dhu = $planning_data->avg('sewing_dhu');
                                         // show only 2 decimal points
                                         $sewing_dhu = number_format($sewing_dhu, 2);
                                         echo $sewing_dhu;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $total_production = $planning_data->sum('Total_Production');
                                         echo $total_production;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $total_alter = $planning_data->sum('Total_Alter');
                                         echo $total_alter;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $total_check = $planning_data->sum('total_check');
                                         echo $total_check;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $uneven_shape = $planning_data->sum('Uneven_Shape');
                                         echo $uneven_shape;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $broken_stitch = $planning_data->sum('Broken_Stitch');
                                         echo $broken_stitch;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $dirty_mark = $planning_data->sum('Dirty_Mark');
                                         echo $dirty_mark;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $oil_stain = $planning_data->sum('Oil_Stain');
                                         echo $oil_stain;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $down_stitch = $planning_data->sum('Down_Stitch');
                                         echo $down_stitch;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $hiking = $planning_data->sum('Hiking');
                                         echo $hiking;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $improper_tuck = $planning_data->sum('Improper_Tuck');
                                         echo $improper_tuck;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $label_alter = $planning_data->sum('Label_Alter');
                                         echo $label_alter;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $needle_mark_hole = $planning_data->sum('Needle_Mark_Hole');
                                         echo $needle_mark_hole;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $open_seam = $planning_data->sum('Open_Seam');
                                         echo $open_seam;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $skip_stitch = $planning_data->sum('Skip_Stitch');
                                         echo $skip_stitch;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $pleat = $planning_data->sum('Pleat');
                                         echo $pleat;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $sleeve_shoulder_up_down = $planning_data->sum('Sleeve_Shoulder_Up_Down');
                                         echo $sleeve_shoulder_up_down;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $puckering = $planning_data->sum('Puckering');
                                         echo $puckering;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $raw_edge = $planning_data->sum('Raw_Edge');
                                         echo $raw_edge;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $shading = $planning_data->sum('Shading');
                                         echo $shading;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $others = $planning_data->sum('Others');
                                         echo $others;
                                     @endphp
                                 </strong>
                             </td>

                             <td><strong>Total</strong>
                             </td>
                             <td><strong>
                                     @php
                                         $reject_dhu = $planning_data->avg('reject_dhu');
                                         // show only 2 decimal points
                                         $reject_dhu = number_format($reject_dhu, 2);
                                         echo $reject_dhu;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $Total_reject = $planning_data->sum('Total_reject');
                                         echo $Total_reject;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $reject_Fabric_hole = $planning_data->sum('reject_Fabric_hole');
                                         echo $reject_Fabric_hole;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $reject_scissor_cuttar_cut = $planning_data->sum('reject_scissor_cuttar_cut');
                                         echo $reject_scissor_cuttar_cut;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $reject_Needle_hole = $planning_data->sum('reject_Needle_hole');
                                         echo $reject_Needle_hole;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $reject_Print_emb_damage = $planning_data->sum('reject_Print_emb_damage');
                                         echo $reject_Print_emb_damage;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $reject_Shading = $planning_data->sum('reject_Shading');
                                         echo $reject_Shading;
                                     @endphp
                                 </strong>
                             </td>
                             <td><strong>
                                     @php
                                         $reject_Others = $planning_data->sum('reject_Others');
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
     <!-- /.container-fluid -->
     </div>
     <!-- /.content -->
     </div>
     <!-- /.content-wrapper -->

     <script>
         function downloadExcelFile() {
             var tab_text = "<table border='2px'><tr bgcolor='#87AFC6'>";
             var tab = document.getElementById('ProductionDataTable').querySelector('table'); // Target the inner table

             // Loop through each row in the table
             for (var j = 0; j < tab.rows.length; j++) {
                 var row = tab.rows[j];
                 var rowHtml = "<tr>";

                 // Loop through each cell in the row
                 for (var k = 0; k < row.cells.length; k++) {
                     var cell = row.cells[k];

                     // Check if the first row contains "Action" or skip the first column for subsequent rows
                     if (j === 0 && cell.innerText.includes('Action')) continue; // Skip "Action" header
                     if (j > 0 && k === 0 && tab.rows[0].cells[0].innerText.includes('Action'))
                         continue; // Skip "Action" column cells for body

                     rowHtml += cell.outerHTML; // Add the cell to the rowHtml
                 }
                 rowHtml += "</tr>";
                 tab_text += rowHtml; // Append the row to the table
             }

             tab_text += "</table>";
             tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); // Remove links
             tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // Remove images
             tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // Remove inputs

             // Create a Blob with the table data
             var blob = new Blob([tab_text], {
                 type: 'application/vnd.ms-excel'
             });

             // Generate a timestamp for uniqueness
             var timestamp = new Date().toISOString().slice(0, 19).replace(/[-T:]/g, '_');

             // Create a link element
             var link = document.createElement('a');
             link.href = URL.createObjectURL(blob);
             link.download = 'ProductionData_' + timestamp + '.xls'; // Filename with timestamp

             // Append the link to the body and trigger the download
             document.body.appendChild(link);
             link.click();

             // Clean up
             document.body.removeChild(link);
         }
     </script>

 </x-backend.layouts.master>

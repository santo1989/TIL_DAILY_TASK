<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Company;
use App\Models\Division;
use App\Models\OldPlanningDataLog;
use App\Models\PlanningData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanningDataController extends Controller
{
    public function fetchData()
    {
        $query = PlanningData::latest()->orderBy('date');

        if (auth()->user()->role_id == 3) {
            // Apply user-specific filtering if the user has role_id == 3
            $query->where('user_id', auth()->user()->id);
        }

        $planning_data = $query->get(); // Fetch all records

        // Return data as JSON
        return response()->json($planning_data);
    }

    public function index()
    {
        $query = PlanningData::latest()->orderBy('date'); // Ensuring consistent ordering

        if (auth()->user()->role_id == 3) {
            // Apply user-specific filtering if the user has role_id == 3
            $query->where('user_id', auth()->user()->id);
        }

        $planning_data = $query->get(); // Fetch all records

        return view('backend.library.planning_data.index', compact('planning_data')); // Return the page with the table content
    }


    public function old_index()
    {
        // Fetching past planning data with pagination
        $query = OldPlanningDataLog::latest()->orderBy('date');

        if (auth()->user()->role_id == 3) {
            // Apply user-specific filtering if the user has role_id == 3
            $query->where('user_id', auth()->user()->id);
        }

        $planning_data = $query->get(); // Fetch all records

        return view('backend.library.planning_data.old_index', compact('planning_data'));
    }


    public function create()
    {
        return view('backend.library.planning_data.create');
    }
    public function store(Request $request)
    {
        $batchId = now()->timestamp; // Generate a unique batch ID using the current timestamp

        // Extract arrays from the request with default values
        $buyerIds = $request->buyer_id ?? [];
        $styles = $request->style ?? [];
        $items = $request->item ?? [];
        $orderQtys = $request->order_qty ?? [];
        $allocateQtys = $request->allocate_qty ?? [];
        $sewingStart = $request->sewing_start ?? [];
        $sewingEnd = $request->sewing_end ?? [];
        $requiredManPower = $request->required_man_power ?? [];
        $averageWorkingHour = $request->average_working_hour ?? [];
        $expectedEfficiency = $request->expected_efficiency ?? [];
        $remarks = $request->remarks ?? [];
        $floors = $request->floor ?? [];

        // Use a transaction for atomic operations
        DB::transaction(function () use (
            $request,
            $batchId,
            &$buyerIds,
            $styles,
            $items,
            $orderQtys,
            $allocateQtys,
            $sewingStart,
            $sewingEnd,
            $requiredManPower,
            $averageWorkingHour,
            $expectedEfficiency,
            $remarks,
            $floors
        ) {
            // Handle buyer_id replacements
            foreach ($buyerIds as $index => $buyerId) {
                if ($buyerId === 'new_buyer') {
                    $newBuyerName = $request->new_buyer_name[$index] ?? null;
                    if ($newBuyerName) {
                        $existingBuyer = Buyer::firstOrCreate(
                            [
                                'name' => strtoupper($newBuyerName),
                                'company_id' => $request->company_id,
                                'division_id' => $request->division_id
                            ]
                        );
                        $buyerIds[$index] = $existingBuyer->id;
                    }
                }
            }

            // Handle style replacements
            foreach ($styles as $index => $style) {
                $styles[$index] = $style === 'new_style'
                    ? strtoupper($request->new_style_name[$index] ?? 'new_style')
                    : strtoupper($style);
            }

            // Handle item replacements
            foreach ($items as $index => $item) {
                $items[$index] = $item === 'other'
                    ? strtoupper($request->new_item_name[$index] ?? 'other')
                    : strtoupper($item);
            }

            // Loop through each floor and insert corresponding records into the planning_data table
            foreach ($floors as $floor) {
                foreach ($request->line as $index => $line) {
                    $buyerId = $buyerIds[$index] ?? null;
                    $buyer = Buyer::find($buyerId); // Fetch buyer if exists
                    $buyerName = $buyer->name ?? 'Unknown Buyer';
                    $style = $styles[$index] ?? 'Unknown Style';
                    $item = $items[$index] ?? 'Unknown Item';
                    $orderQty = $orderQtys[$index] ?? 0;
                    $allocateQty = $allocateQtys[$index] ?? 0;
                    $sewingStartTime = $sewingStart[$index] ?? null;
                    $sewingEndTime = $sewingEnd[$index] ?? null;
                    $requiredManPowerVal = $requiredManPower[$index] ?? null;
                    $averageWorkingHourVal = $averageWorkingHour[$index] ?? null;
                    $expectedEfficiencyVal = $expectedEfficiency[$index] ?? null;
                    $remark = $remarks[$index] ?? null;

                    // Save the new WHA
                    PlanningData::create([
                        'division_id' => $request->division_id,
                        'company_id' => $request->company_id,
                        'company_name' => Company::findOrFail($request->company_id)->name,
                        'division_name' => Division::findOrFail($request->division_id)->name,
                        'date' => now()->format('Y-m-d'),
                        'floor' => $floor,
                        'line' => $line,
                        'batch_id' => $batchId,
                        'buyer_id' => $buyerId,
                        'buyer' => $buyerName,
                        'style' => $style,
                        'item' => $item,
                        'order_qty' => $orderQty,
                        'allocate_qty' => $allocateQty,
                        'sewing_start_time' => $sewingStartTime,
                        'sewing_end_time' => $sewingEndTime,
                        'required_man_power' => $requiredManPowerVal,
                        'average_working_our' => $averageWorkingHourVal,
                        'expected_efficiency' => $expectedEfficiencyVal,
                        'remarks' => $remark,
                        'user_id' => auth()->user()->id,
                        'data_entry_by' => auth()->user()->name,
                    ]);
                }
            }
        });

        // Redirect
        return redirect()->route('planning_data.index')->withMessage('Planning Data created successfully!');
    }




    public function show(PlanningData $workingHourAllocation)
    {
        //
    }


    public function edit($wha)
    {
        $planning_data = PlanningData::findorFail($wha);
        // dd($planning_data);
        return view('backend.library.planning_data.edit', compact('planning_data'));
    }

    public function update(Request $request)
    {
        // dd($request->all());
        $batchId = now()->timestamp; // Generate a unique batch ID using the current timestamp

        // Extract arrays from the request with default values
        $buyerIds = $request->buyer_id ?? [];
        $styles = $request->style ?? [];
        $items = $request->item ?? [];
        $line = $request->line ?? [];
        $smv = $request->smv ?? [];
        $orderQtys = $request->order_qty ?? [];
        $allocateQtys = $request->allocate_qty ?? [];
        $sewingStart = $request->sewing_start ?? [];
        $sewingEnd = $request->sewing_end ?? [];
        $requiredManPower = $request->required_man_power ?? [];
        $averageWorkingHour = $request->average_working_hour ?? [];
        $expectedEfficiency = $request->expected_efficiency ?? [];
        $remarks = $request->remarks ?? [];
        $floors = $request->floor ?? [];
       

        // Use a transaction for atomic operations
        DB::transaction(function () use (
            $request,
            $batchId,
            &$buyerIds,
            $styles,
            $items,
            $line,
            $orderQtys,
            $allocateQtys,
            $sewingStart,
            $sewingEnd,
            $requiredManPower,
            $averageWorkingHour,
            $expectedEfficiency,
            $remarks,
            $floors, $smv
        ) {
            // Handle buyer_id replacements
            foreach ($buyerIds as $index => $buyerId) {
                if ($buyerId === 'new_buyer') {
                    $newBuyerName = $request->new_buyer_name[$index] ?? null;
                    if ($newBuyerName) {
                        $existingBuyer = Buyer::firstOrCreate(
                            [
                                'name' => strtoupper($newBuyerName),
                                'company_id' => $request->company_id,
                                'division_id' => $request->division_id
                            ]
                        );
                        $buyerIds[$index] = $existingBuyer->id;
                    }
                }
            }

            // Handle style replacements
            foreach ($styles as $index => $style) {
                $styles[$index] = $style === 'new_style'
                ? strtoupper($request->new_style_name[$index] ?? 'new_style')
                : strtoupper($style);
            }

            // Handle item replacements
            foreach ($items as $index => $item) {
                $items[$index] = $item === 'other'
                ? strtoupper($request->new_item_name[$index] ?? 'other')
                : strtoupper($item);
            }

            // Loop through each floor and update corresponding records into the planning_data table
            foreach ($floors as $floor) {
                foreach ($request->line as $index => $line) {
                    $buyerId = $buyerIds[$index] ?? null;
                    $buyer = Buyer::find($buyerId); // Fetch buyer if exists
                    $buyerName = $buyer->name ?? '';
                    $style = $styles[$index] ?? '';
                    $item = $items[$index] ?? '';
                    $Line = $line;
                    $orderQty = $orderQtys[$index] ?? 0;
                    $allocateQty = $allocateQtys[$index] ?? 0;
                    $sewingStartTime = $sewingStart[$index] ?? null;
                    $sewingEndTime = $sewingEnd[$index] ?? null;
                    $requiredManPowerVal = $requiredManPower[$index] ?? null;
                    $averageWorkingHourVal = $averageWorkingHour[$index] ?? null;
                    $expectedEfficiencyVal = $expectedEfficiency[$index] ?? null;
                    $remark = $remarks[$index] ?? null;
                    $smvVal = $smv[$index] ?? null;

                    // Log the old planning data
                    $existingPlanningData = PlanningData::where('id',  $request->planning_data_id)->first();

                    if ($existingPlanningData) {
                        // Compare the old and new data to see if there are any changes
                        $oldData = $existingPlanningData->getAttributes();
                        $newData = [
                            'buyer_id' => $buyerId,
                            'style' => $style,
                            'item' => $item,
                            'line' => $Line,
                            'order_qty' => $orderQty,
                            'allocate_qty' => $allocateQty,
                            'sewing_start_time' => $sewingStartTime,
                            'sewing_end_time' => $sewingEndTime,
                            'required_man_power' => $requiredManPowerVal,
                            'average_working_our' => $averageWorkingHourVal,
                            'expected_efficiency' => $expectedEfficiencyVal,
                            'remarks' => $remark,
                            'smv' => $smvVal,
                        ];

                        // Compare the data, log only if there is a change
                        $changes = [];
                        foreach ($oldData as $key => $value) {
                            if (isset($newData[$key]) && $value !== $newData[$key]) {
                                $changes[$key] = ['old' => $value, 'new' => $newData[$key]];
                            }
                        }

                        // If any changes exist, log them
                        if (!empty($changes)) {
                            OldPlanningDataLog::create([
                                'batch_id' => $batchId,
                                'division_id' => $request->division_id,
                                'company_id' => $request->company_id,
                                'division_name' => Division::findOrFail($request->division_id)->name,
                                'company_name' => Company::findOrFail($request->company_id)->name,
                                'planning_data_id' => $existingPlanningData->id,
                                'date' => now()->format('Y-m-d'),
                                'old_floor' => $existingPlanningData->floor,
                                'old_line' => $existingPlanningData->line,
                                'old_section' => $existingPlanningData->section,
                                'old_buyer' => $existingPlanningData->buyer,
                                'old_buyer_id' => $existingPlanningData->buyer_id,
                                'old_style' => $existingPlanningData->style,
                                'old_item' => $existingPlanningData->item,
                                'old_order_qty' => $existingPlanningData->order_qty,
                                'old_allocate_qty' => $existingPlanningData->allocate_qty,
                                'old_sewing_start_time' => $existingPlanningData->sewing_start_time,
                                'old_sewing_end_time' => $existingPlanningData->sewing_end_time,
                                'old_required_man_power' => $existingPlanningData->required_man_power,
                                'old_average_working_our' => $existingPlanningData->average_working_our,
                                'old_expected_efficiency' => $existingPlanningData->expected_efficiency,
                                'old_remarks' => $existingPlanningData->remarks,
                                'user_id' => auth()->user()->id,
                                'data_edit_by' => auth()->user()->name, 
                                'old_smv' => $existingPlanningData->smv,
                            ]);
                        }

                        // Update the record only if data has changed
                        $existingPlanningData->update($newData);
                    }
                }
            }
        });

        // Redirect with success message
        return redirect()->route('planning_data.index')->withMessage('Planning Data updated successfully!');
    }

  



    public function destroy($planning_data)
    {
        // dd($planning_data);
        $wha = PlanningData::findorFail($planning_data);
        // dd($wha);
        $wha->delete();
        return redirect()->route('planning_data.index')->withMessage('Planning Data deleted successfully!');
    }


    public function line_destroy(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'buyer_id' => 'required',
            'floor' => 'required',
            'line' => 'required',
            'style' => 'required',
            'item' => 'required',
        ]);

        try {
            DB::beginTransaction(); // Start transaction

            $deletedRows = PlanningData::where('date', $request->date)
                ->where('buyer_id', $request->buyer_id)
                ->where('floor', $request->floor)
                ->where('line', $request->line)
                ->where('style', $request->style)
                ->where('item', $request->item)
                ->delete();

            DB::commit(); // Commit transaction

            return back()->withMessage('Records deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error

            return back()->with('error', 'An error occurred while deleting records.');
        }
    }

    public function search(Request $request)
    {
        $query = DB::table('working_hour_allocations');

        if ($request->has('buyer_id') && $request->buyer_id) {
            $query->where('buyer_id', $request->buyer_id);
        }
        if ($request->has('style') && $request->style) {
            $query->where('style', $request->style);
        }
        if ($request->has('item') && $request->item) {
            $query->where('item', $request->item);
        }
        if ($request->has('line') && $request->line) {
            $query->where('line', $request->line);
        }

        // Fetch distinct values based on current filters
        $buyers = $query->select('buyer_id', 'buyer')->distinct()->get();
        $styles = $query->select('style')->distinct()->get();
        $items = $query->select('item')->distinct()->get();
        $lines = $query->select('line')->distinct()->get();

        return response()->json([
            'buyers' => $buyers,
            'styles' => $styles,
            'items' => $items,
            'lines' => $lines,
        ]);
    }




    public function generateReport(Request $request)
    {
        $query = DB::table('working_hour_allocations');

        if ($request->has('buyer_id') && $request->buyer_id) {
            $query->where('buyer_id', $request->buyer_id);
        }
        if ($request->has('item') && $request->item) {
            $query->where('item', $request->item);
        }
        if ($request->has('line') && $request->line) {
            $query->where('line', $request->line);
        }
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('date', '>=', $request->from_date);
        }
        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('date', '<=', $request->to_date);
        }

        $planning_data = $query->get();

        // dd($planning_data); // Adjust as needed

        // Generate and return the report (e.g., as a PDF or CSV)
        return view('backend.library.planning_data.report_mail', compact('planning_data'));
    }


    public function todayReport()
    {
        $query = DB::table('working_hour_allocations');

        $query->whereDate('date', now()->format('Y-m-d')); // Fetch records for today

        $planning_data = $query->get();

        // dd($planning_data); // Adjust as needed

        // Generate and return the report (e.g., as a PDF or CSV)
        return view('backend.library.planning_data.show', compact('planning_data'));
    }

    public function todayGraph()
    {
        $today = now()->format('Y-m-d');

        // Fetch alteration data grouped by line
        $alterationData = PlanningData::select(
            // 'line',
            DB::raw('SUM(Uneven_Shape) as total_Uneven_Shape'),
            DB::raw('SUM(Broken_Stitch) as total_Broken_Stitch'),
            DB::raw('SUM(Dirty_Mark) as total_Dirty_Mark'),
            DB::raw('SUM(Oil_Stain) as total_Oil_Stain'),
            DB::raw('SUM(Down_Stitch) as total_Down_Stitch'),
            DB::raw('SUM(Hiking) as total_Hiking'),
            DB::raw('SUM(Improper_Tuck) as total_Improper_Tuck'),
            DB::raw('SUM(Label_Alter) as total_Label_Alter'),
            DB::raw('SUM(Needle_Mark_Hole) as total_Needle_Mark_Hole'),
            DB::raw('SUM(Open_Seam) as total_Open_Seam'),
            DB::raw('SUM(Skip_Stitch) as total_Skip_Stitch'),
            DB::raw('SUM(Pleat) as total_Pleat'),
            DB::raw('SUM(Sleeve_Shoulder_Up_Down) as total_Sleeve_Shoulder_Up_Down'),
            DB::raw('SUM(Puckering) as total_Puckering'),
            DB::raw('SUM(Raw_Edge) as total_Raw_Edge'),
            DB::raw('SUM(Shading) as total_Shading'),
            DB::raw('SUM(Uncut_Thread) as total_Uncut_Thread'),
            DB::raw('SUM(Others) as total_Others')
        )
            // ->groupBy('line')
            ->whereDate('date', $today)
            ->get();

        // Fetch rejection data
        $rejectionData = PlanningData::select(
            DB::raw('SUM(reject_Fabric_hole) as total_reject_Fabric_hole'),
            DB::raw('SUM(reject_scissor_cuttar_cut) as total_reject_scissor_cuttar_cut'),
            DB::raw('SUM(reject_Needle_hole) as total_reject_Needle_hole'),
            DB::raw('SUM(reject_Print_emb_damage) as total_reject_Print_emb_damage'),
            DB::raw('SUM(reject_Shading) as total_reject_Shading'),
            DB::raw('SUM(reject_Others) as total_reject_Others')
        )->whereDate('date', $today)->get();

        // dd($rejectionData);
        // Fetch total production data grouped by line
        $totalData = PlanningData::select(
            'line',
            DB::raw('SUM(Total_Production) as total_production')
        )->whereDate('date', $today)->groupBy('line')
            ->get();

        // Query to get total production, reject, and alter counts by buyer
        $buyerdata = DB::table('working_hour_allocations')
            ->select(
                'buyer',
                DB::raw('SUM(Total_Production) as total_production'),
                DB::raw('SUM(Total_reject) as total_reject'),
                DB::raw('SUM(Total_Alter) as total_alter')
            )->whereDate('date', $today)
            ->groupBy('buyer')
            ->get();

        // Query to get total production, reject, and alter counts by item
        $itemdata = DB::table('working_hour_allocations')
            ->select(
                'item',
                DB::raw('SUM(Total_Production) as total_production'),
                DB::raw('SUM(Total_reject) as total_reject'),
                DB::raw('SUM(Total_Alter) as total_alter')
            )->whereDate('date', $today)
            ->groupBy('item')
            ->get();

        // Query to get total production, reject, and alter counts by day
        $daydata = DB::table('working_hour_allocations')
            ->select(
                'date',
                DB::raw('SUM(Total_Production) as total_production'),
                DB::raw('SUM(Total_reject) as total_reject'),
                DB::raw('SUM(Total_Alter) as total_alter')
            )
            ->groupBy('date')
            ->orderBy('date') // Ensure data is ordered by date
            ->get();

        //All data of today filter by date



        // dd($alterationData, $rejectionData, $totalData, $buyerdata, $itemdata, $daydata); // Adjust as needed
        // Return the view with data
        return view('backend.library.planning_data.todayGraph', compact('alterationData', 'rejectionData', 'totalData', 'buyerdata', 'itemdata', 'daydata'));
    }


    public function trg()
    {
        $today = now()->format('Y-m-d');

        // Fetch alteration data grouped by line
        $alterationData = PlanningData::select(
            // 'line',
            DB::raw('SUM(Uneven_Shape) as total_Uneven_Shape'),
            DB::raw('SUM(Broken_Stitch) as total_Broken_Stitch'),
            DB::raw('SUM(Dirty_Mark) as total_Dirty_Mark'),
            DB::raw('SUM(Oil_Stain) as total_Oil_Stain'),
            DB::raw('SUM(Down_Stitch) as total_Down_Stitch'),
            DB::raw('SUM(Hiking) as total_Hiking'),
            DB::raw('SUM(Improper_Tuck) as total_Improper_Tuck'),
            DB::raw('SUM(Label_Alter) as total_Label_Alter'),
            DB::raw('SUM(Needle_Mark_Hole) as total_Needle_Mark_Hole'),
            DB::raw('SUM(Open_Seam) as total_Open_Seam'),
            DB::raw('SUM(Skip_Stitch) as total_Skip_Stitch'),
            DB::raw('SUM(Pleat) as total_Pleat'),
            DB::raw('SUM(Sleeve_Shoulder_Up_Down) as total_Sleeve_Shoulder_Up_Down'),
            DB::raw('SUM(Puckering) as total_Puckering'),
            DB::raw('SUM(Raw_Edge) as total_Raw_Edge'),
            DB::raw('SUM(Shading) as total_Shading'),
            DB::raw('SUM(Uncut_Thread) as total_Uncut_Thread'),
            DB::raw('SUM(Others) as total_Others')
        )
            // ->groupBy('line')
            ->whereDate('date', $today)
            ->orderBy(DB::raw('SUM(Uneven_Shape)'), 'desc')
            ->orderBy(DB::raw('SUM(Broken_Stitch)'), 'desc')
            ->orderBy(DB::raw('SUM(Dirty_Mark)'), 'desc')
            ->orderBy(DB::raw('SUM(Oil_Stain)'), 'desc')
            ->orderBy(DB::raw('SUM(Down_Stitch)'), 'desc')
            ->orderBy(DB::raw('SUM(Hiking)'), 'desc')
            ->orderBy(DB::raw('SUM(Improper_Tuck)'), 'desc')
            ->orderBy(DB::raw('SUM(Label_Alter)'), 'desc')
            ->orderBy(DB::raw('SUM(Needle_Mark_Hole)'), 'desc')
            ->orderBy(DB::raw('SUM(Open_Seam)'), 'desc')
            ->orderBy(DB::raw('SUM(Skip_Stitch)'), 'desc')
            ->orderBy(DB::raw('SUM(Pleat)'), 'desc')
            ->orderBy(DB::raw('SUM(Sleeve_Shoulder_Up_Down)'), 'desc')
            ->orderBy(DB::raw('SUM(Puckering)'), 'desc')
            ->orderBy(DB::raw('SUM(Raw_Edge)'), 'desc')
            ->orderBy(DB::raw('SUM(Shading)'), 'desc')
            ->orderBy(DB::raw('SUM(Uncut_Thread)'), 'desc')
            ->orderBy(DB::raw('SUM(Others)'), 'desc')
            ->get();

        // Fetch rejection data
        $rejectionData = PlanningData::select(
            DB::raw('SUM(reject_Fabric_hole) as total_reject_Fabric_hole'),
            DB::raw('SUM(reject_scissor_cuttar_cut) as total_reject_scissor_cuttar_cut'),
            DB::raw('SUM(reject_Needle_hole) as total_reject_Needle_hole'),
            DB::raw('SUM(reject_Print_emb_damage) as total_reject_Print_emb_damage'),
            DB::raw('SUM(reject_Shading) as total_reject_Shading'),
            DB::raw('SUM(reject_Others) as total_reject_Others')
        )
            ->whereDate('date', $today)
            ->orderBy(DB::raw('SUM(reject_Fabric_hole)'), 'desc')
            ->orderBy(DB::raw('SUM(reject_scissor_cuttar_cut)'), 'desc')
            ->orderBy(DB::raw('SUM(reject_Needle_hole)'), 'desc')
            ->orderBy(DB::raw('SUM(reject_Print_emb_damage)'), 'desc')
            ->orderBy(DB::raw('SUM(reject_Shading)'), 'desc')
            ->orderBy(DB::raw('SUM(reject_Others)'), 'desc')
            ->get();

        // dd($rejectionData);
        // Fetch total production data grouped by line
        $totalData = PlanningData::select(
            'line',
            DB::raw('SUM(Total_Production) as total_production')
        )->whereDate('date', $today)->groupBy('line')
            ->get();

        // Query to get total production, reject, and alter counts by buyer
        $buyerdata = DB::table('working_hour_allocations')
            ->select(
                'buyer',
                DB::raw('SUM(Total_Production) as total_production'),
                DB::raw('SUM(Total_reject) as total_reject'),
                DB::raw('SUM(Total_Alter) as total_alter')
            )->whereDate('date', $today)
            ->groupBy('buyer')
            ->get();

        // Query to get total production, reject, and alter counts by item
        $itemdata = DB::table('working_hour_allocations')
            ->select(
                'item',
                DB::raw('SUM(Total_Production) as total_production'),
                DB::raw('SUM(Total_reject) as total_reject'),
                DB::raw('SUM(Total_Alter) as total_alter')
            )->whereDate('date', $today)
            ->groupBy('item')
            ->get();

        // Query to get total production, reject, and alter counts by day
        $daydata = DB::table('working_hour_allocations')
            ->select(
                'date',
                DB::raw('SUM(Total_Production) as total_production'),
                DB::raw('SUM(Total_reject) as total_reject'),
                DB::raw('SUM(Total_Alter) as total_alter')
            )
            ->groupBy('date')
            ->orderBy('date') // Ensure data is ordered by date
            ->get();

        //All data of today filter by date



        // dd($alterationData, $rejectionData, $totalData, $buyerdata, $itemdata, $daydata); // Adjust as needed
        // Return the view with data
        return view('backend.library.planning_data.trg', compact('alterationData', 'rejectionData', 'totalData', 'buyerdata', 'itemdata', 'daydata'));
    }
}

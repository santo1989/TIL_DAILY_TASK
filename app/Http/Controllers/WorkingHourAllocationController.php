<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Company;
use App\Models\Division;
use App\Models\WorkingHourAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkingHourAllocationController extends Controller
{
    public function index()
    {
        $query = WorkingHourAllocation::select('date', 'buyer_id', 'floor', 'line', 'buyer', 'style', 'item', 'section')
            ->whereDate('date', now()->format('Y-m-d'))
            ->groupBy('date', 'buyer_id', 'floor', 'line', 'buyer', 'style', 'item', 'section')
            ->orderBy('date'); // Ensuring consistent ordering

        if (auth()->user()->role_id == 3) {
            // Apply user-specific filtering if the user has role_id == 3
            $query->where('user_id', auth()->user()->id);
        }

        $planning_data = $query->get(); // Fetch all records

        return view('backend.library.planning_data.index', compact('planning_data')); // Return the whole page with the table content
    }


    public function old_index()
    {
        // Similar pagination and ordering
        $planning_data = WorkingHourAllocation::select('date', 'buyer_id', 'floor', 'line', 'buyer', 'style', 'item')
            ->whereDate('date', '!=', now()->format('Y-m-d'))
            ->groupBy('date', 'buyer_id', 'floor', 'line', 'buyer', 'style', 'item')
            ->orderBy('date') // Consistent ordering
            ->paginate(50); // Fetch results in batches of 50

        return view('backend.library.planning_data.old_index', compact('planning_data'));
    }


    public function create()
    {
        return view('backend.library.planning_data.create');
    }
    public function store(Request $request)
    {
        // dd($request->all());
        // Validate the incoming request
        $request->validate([
            'company_id' => 'required',
            'division_id' => 'required',
            'floor' => 'required',
            'section' => 'required',
            'line' => 'required|array',
            'start_time' => 'required',
            'end_time' => 'required',
            'buyer_id' => 'required|array',
            'style' => 'required|array',
            'item' => 'required|array',
            'new_buyer_name' => 'nullable|array',
            'new_style_name' => 'nullable|array',
            'new_item_name' => 'nullable|array',
        ]);

        $batchId = now()->timestamp; // Generate a unique batch ID using the current timestamp

        // Extract arrays from the request
        $buyerIds = $request->buyer_id;
        $styles = $request->style;
        $items = $request->item;

        // Use a transaction to ensure atomic operations
        DB::transaction(function () use ($request, $batchId, &$buyerIds, $styles, $items) {
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
                if ($style === 'new_style') {
                    $styles[$index] = strtoupper($request->new_style_name[$index] ?? $style);
                }
            }

            // Handle item replacements
            foreach ($items as $index => $item) {
                if ($item === 'other') {
                    $items[$index] = strtoupper($request->new_item_name[$index] ?? $item);
                }
            }

            // Get existing WHAs for validation
            $existingWHAs = WorkingHourAllocation::where('company_id', $request->company_id)
                ->where('division_id', $request->division_id)
                ->where('floor', $request->floor)
                ->where('date', now()->format('Y-m-d'))
                ->where('start_time', $request->start_time)
                ->get();

            foreach ($request->line as $index => $line) {
                // Check if WHA already exists for the line
                if ($existingWHAs->contains('line', $line)) {
                    return redirect()->route('planning_data.index')->withErrors('Working Hour Allocation already exists for line ' . $line);
                }

                $buyerId = $buyerIds[$index];
                $buyer = Buyer::findOrFail($buyerId); // Ensure buyer ID is valid
                $buyerName = $buyer->name;
                $style = $styles[$index];
                $item = $items[$index];

                // Save the new WHA
                WorkingHourAllocation::create([
                    'division_id' => $request->division_id,
                    'company_id' => $request->company_id,
                    'company_name' => Company::findOrFail($request->company_id)->name,
                    'division_name' => Division::findOrFail($request->division_id)->name,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'date' => now()->format('Y-m-d'),
                    'floor' => $request->floor,
                    'section' => $request->section,
                    'line' => $line,
                    'batch_id' => $batchId,
                    'buyer_id' => $buyerId,
                    'buyer' => $buyerName,
                    'style' => strtoupper($style),
                    'item' => strtoupper($item),
                    'user_id' => auth()->user()->id,
                ]);
            }
        });

        // Redirect
        return redirect()->route('planning_data.index')->withMessage('Working Hour Allocation created successfully!');
    }


    public function timeEntry(Request $request, $planning_data_timeEntry_id)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'planning_data_timeEntry_id' => 'required'
            ]);

            $planning_data = WorkingHourAllocation::findOrFail($request->planning_data_timeEntry_id);
            return view('backend.library.planning_data.alter_entery', compact('planning_data'));
        }

        // Handle return back to index,
        return redirect()->route('planning_data.index')->withMessage('Try Agin Plz');
    }


    public function timeEntry_copy(Request $request, $planning_data_timeEntry_id)
    {
        // Validate request
        $request->validate([
            'planning_data_timeEntry_id' => 'required|exists:working_hour_allocations,id'
        ]);

        // Use a transaction to ensure atomicity and avoid deadlocks
        DB::beginTransaction();

        try {
            // Find the existing WorkingHourAllocation
            $planning_data = WorkingHourAllocation::findOrFail($planning_data_timeEntry_id);

            //Find the last WorkingHourAllocation record for the same line, floor, buyer, style, and item on the same date
            $last_planning_data = WorkingHourAllocation::where('date', $planning_data->date)
                ->where('floor', $planning_data->floor)
                ->where('line', $planning_data->line)
                ->where('buyer_id', $planning_data->buyer_id)
                ->where('style', $planning_data->style)
                ->where('item', $planning_data->item)
                ->orderBy('end_time', 'desc')
                ->first();


            // Calculate new start and end times
            $start_time = $last_planning_data->end_time;
            $end_time = date('H:i', strtotime($last_planning_data->end_time) + 3600); // Adding one hour (3600 seconds) instead of one second to avoid edge cases

            // Create a new WorkingHourAllocation record
            $new_planning_data = new WorkingHourAllocation;
            $new_planning_data->company_id = $last_planning_data->company_id;
            $new_planning_data->division_id = $last_planning_data->division_id;
            $new_planning_data->company_name = $last_planning_data->company_name;
            $new_planning_data->division_name = $last_planning_data->division_name;
            $new_planning_data->start_time = $start_time;
            $new_planning_data->end_time = $end_time;
            $new_planning_data->date = now()->format('Y-m-d');
            $new_planning_data->floor = $last_planning_data->floor;
            $new_planning_data->line = $last_planning_data->line;
            $new_planning_data->section = $last_planning_data->section;
            $new_planning_data->batch_id = $last_planning_data->batch_id;
            $new_planning_data->buyer_id = $last_planning_data->buyer_id;
            $new_planning_data->buyer = $last_planning_data->buyer;
            $new_planning_data->style = $planning_data->style;
            $new_planning_data->item = $last_planning_data->item;
            $new_planning_data->save();
            $planning_data = $new_planning_data;
            // Commit the transaction
            DB::commit();

            // Pass the new WorkingHourAllocation to the view
            return view('backend.library.planning_data.alter_entery', compact('planning_data'));
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            // Optionally, log the exception or handle it as needed
            return back()->withErrors(['error' => 'Failed to copy time entry: ' . $e->getMessage()]);
        }
    }


    public function alterdata_store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'alter_id' => 'required',
            'total_alter' => 'required',
            'total_production' => 'required',
            'sewing_dhu' => 'required',
            'total_check' => 'required',
        ]);

        // dd($request->all());
        $planning_data = WorkingHourAllocation::findorFail($request->alter_id);
        // dd($planning_data);

        $planning_data->total_alter = $request->total_alter;
        $planning_data->total_production = $request->total_production;
        $planning_data->sewing_dhu = $request->sewing_dhu;
        $planning_data->total_check = $request->total_check;
        $planning_data->uneven_shape = $request->uneven__shape;
        $planning_data->broken_stitch = $request->broken__stitch;
        $planning_data->dirty_mark = $request->dirty__mark;
        $planning_data->oil_stain = $request->oil__stain;
        $planning_data->down_stitch = $request->down__stitch;
        $planning_data->hiking = $request->hiking;
        $planning_data->improper_tuck = $request->improper__tuck;
        $planning_data->label_alter = $request->label__alter;
        $planning_data->needle_mark_hole = $request->needle__mark__hole;
        $planning_data->open_seam = $request->open__seam;
        $planning_data->skip_stitch = $request->skip__stitch;
        $planning_data->pleat = $request->pleat;
        $planning_data->sleeve_shoulder_up_down = $request->sleeve__shoulder__up__down;
        $planning_data->puckering = $request->puckering;
        $planning_data->raw_edge = $request->raw__edge;
        $planning_data->shading = $request->shading;
        $planning_data->uncut_thread = $request->uncut__thread;
        $planning_data->others = $request->others;
        //reject Data
        $planning_data->Total_reject = $request->total_reject;
        $planning_data->reject_dhu = $request->total_reject_perent;
        $planning_data->reject_Fabric_hole = $request->fabric_hole__reject;
        $planning_data->reject_scissor_cuttar_cut = $request->scissor_cuttar_cut__reject;
        $planning_data->reject_Needle_hole = $request->needle_hole__reject;
        $planning_data->reject_Print_emb_damage = $request->print__e_m_b_damage__reject;
        $planning_data->reject_Shading = $request->shading__reject;
        $planning_data->reject_Others = $request->others__reject;
        //reject Data
        $planning_data->remarks = $request->remarks;
        $planning_data->user_id = auth()->user()->id;
        $planning_data->line_incharge = auth()->user()->name;
        $planning_data->save();

        return redirect()->route('planning_data.index')->withMessage('Working Hour Allocation updated successfully!');
    }
    public function show(WorkingHourAllocation $workingHourAllocation)
    {
        //
    }


    public function edit($wha)
    {
        $planning_data = WorkingHourAllocation::findorFail($wha);
        // dd($planning_data);
        return view('backend.library.planning_data.edit', compact('planning_data'));
    }


    public function alterdata_store_update(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'alter_id' => 'required|exists:working_hour_allocations,id',
            'total_alter' => 'required|numeric',
            'total_production' => 'required|numeric',
            'sewing_dhu' => 'required|numeric',
            'total_check' => 'required|numeric',
            'total_reject' => 'required|numeric',
            'total_reject_percent' => 'required|numeric',
            // Add validation for all other fields as needed
        ]);

        // Find the existing WorkingHourAllocation record
        $planning_data = WorkingHourAllocation::findOrFail($request->alter_id);

        // Update the record with new values
        $planning_data->total_alter = $request->total_alter;
        $planning_data->total_production = $request->total_production;
        $planning_data->sewing_dhu = $request->sewing_dhu;
        $planning_data->total_check = $request->total_check;
        $planning_data->total_reject = $request->total_reject;
        $planning_data->reject_dhu = $request->total_reject_percent;

        // Update alter entry fields
        $planning_data->uneven_shape = $request->Uneven_Shape;
        $planning_data->broken_stitch = $request->Broken_Stitch;
        $planning_data->dirty_mark = $request->Dirty_Mark;
        $planning_data->oil_stain = $request->Oil_Stain;
        $planning_data->down_stitch = $request->Down_Stitch;
        $planning_data->hiking = $request->Hiking;
        $planning_data->improper_tuck = $request->Improper_Tuck;
        $planning_data->label_alter = $request->Label_Alter;
        $planning_data->needle_mark_hole = $request->Needle_Mark_Hole;
        $planning_data->open_seam = $request->Open_Seam;
        $planning_data->skip_stitch = $request->Skip_Stitch;
        $planning_data->pleat = $request->Pleat;
        $planning_data->sleeve_shoulder_up_down = $request->Sleeve_Shoulder_Up_Down;
        $planning_data->puckering = $request->Puckering;
        $planning_data->raw_edge = $request->Raw_Edge;
        $planning_data->shading = $request->Shading;
        $planning_data->uncut_thread = $request->Uncut_Thread;
        $planning_data->others = $request->Others;

        // Update reject entry fields
        $planning_data->reject_fabric_hole = $request->Fabric_hole_Reject;
        $planning_data->reject_scissor_cuttar_cut = $request->scissor_cuttar_cut_Reject;
        $planning_data->reject_needle_hole = $request->Needle_hole_Reject;
        $planning_data->reject_print_emb_damage = $request->Print_EMB_damage_Reject;
        $planning_data->reject_shading = $request->Shading_Reject;
        $planning_data->reject_others = $request->Others_Reject;

        // Update remarks if provided
        $planning_data->remarks = $request->remarks ?? $planning_data->remarks;

        // Save the updated record
        $planning_data->save();

        // Redirect with success message
        return redirect()->route('planning_data.index')->withMessages('Working Hour Allocation updated successfully!');
    }



    public function destroy($planning_data)
    {
        // dd($planning_data);
        $wha = WorkingHourAllocation::findorFail($planning_data);
        // dd($wha);
        $wha->delete();
        return redirect()->route('planning_data.index')->withMessage('Working Hour Allocation deleted successfully!');
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

            $deletedRows = WorkingHourAllocation::where('date', $request->date)
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
        $alterationData = WorkingHourAllocation::select(
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
        $rejectionData = WorkingHourAllocation::select(
            DB::raw('SUM(reject_Fabric_hole) as total_reject_Fabric_hole'),
            DB::raw('SUM(reject_scissor_cuttar_cut) as total_reject_scissor_cuttar_cut'),
            DB::raw('SUM(reject_Needle_hole) as total_reject_Needle_hole'),
            DB::raw('SUM(reject_Print_emb_damage) as total_reject_Print_emb_damage'),
            DB::raw('SUM(reject_Shading) as total_reject_Shading'),
            DB::raw('SUM(reject_Others) as total_reject_Others')
        )->whereDate('date', $today)->get();

        // dd($rejectionData);
        // Fetch total production data grouped by line
        $totalData = WorkingHourAllocation::select(
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
        $alterationData = WorkingHourAllocation::select(
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
        $rejectionData = WorkingHourAllocation::select(
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
        $totalData = WorkingHourAllocation::select(
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

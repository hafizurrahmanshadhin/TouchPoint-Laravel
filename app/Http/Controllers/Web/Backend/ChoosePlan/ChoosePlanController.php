<?php

namespace App\Http\Controllers\Web\Backend\ChoosePlan;


use App\Helpers\Helper;
use App\Http\Controllers\Controller;

use App\Models\ChoosePlan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class ChoosePlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ChoosePlan::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($choosePlan) {
                    $status = '<div class="form-check form-switch" style="margin-left: 40px; width: 50px; height: 24px;">';
                    $status .= '<input class="form-check-input" type="checkbox" role="switch" id="SwitchCheck' . $choosePlan->id . '" ' . ($choosePlan->status == 'active' ? 'checked' : '') . ' onclick="showStatusChangeAlert(' . $choosePlan->id . ')">';
                    $status .= '</div>';
                    return $status;
                })
                ->addColumn('action', function ($choosePlan) {
                    return '
                            <div class="hstack gap-3 fs-base">
                                <a href="' . route('choose.plan.edit', $choosePlan) . '" class="link-primary text-decoration-none" title="Edit">
                                    <i class="ri-pencil-line" style="font-size: 24px;"></i>
                                </a>

                                <a href="javascript:void(0);" onclick="showDeleteConfirm(' . $choosePlan->id . ')" class="link-danger text-decoration-none" title="Delete">
                                    <i class="ri-delete-bin-5-line" style="font-size: 24px;"></i>
                                </a>
                            </div>
                        ';
                })

                ->rawColumns(['status', 'action'])
                ->make();
        }

        return view('backend.layouts.choose_plan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $choosePlan = new ChoosePlan();
        return view('backend.layouts.choose_plan.create', compact('choosePlan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|string',
            'button_link' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $choosePlan = new ChoosePlan();
        $choosePlan->title = $request->title;
        $choosePlan->price = $request->price;
        $choosePlan->description = $request->description;
        $choosePlan->button_link = $request->button_link;
        $choosePlan->save();


        return redirect()->route('choose.plan.index')->with('success', 'Choose Plan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ChoosePlan $choosePlan)
    {
        // return view('backend.layouts.choose_plan.show', compact('choosePlan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChoosePlan $choosePlan)
    {

        return view('backend.layouts.choose_plan.edit', compact('choosePlan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChoosePlan $choosePlan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChoosePlan $choosePlan)
    {

        try {
            $choosePlan->delete();

            return response()->json(['t-success' => true, 'message' => 'Choose Plan deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['t-error' => false, 'message' => 'Error deleting Choose Plan.']);
        }
    }

    // Change status
    // public function status(Request $request, $id)
    // {
    //     $choosePlan = ChoosePlan::find($id);
    //     if ($choosePlan) {
    //         $choosePlan->status = $request->status == 'active' ? 'inactive' : 'active';
    //         $choosePlan->save();
    //         return response()->json(['t-success' => true, 'message' => 'Status updated successfully.']);
    //     }
    //     return response()->json(['t-success' => false, 'message' => 'Choose Plan not found.']);
    // }

    // Change status

    public function status(Request $request, $id)
    {
        try {
            $choosePlan = ChoosePlan::findOrFail($id);

            $choosePlan->status = $choosePlan->status === 'active' ? 'inactive' : 'active';
            $choosePlan->save();

            return response()->json([
                't-success' => true,
                'message' => 'Status updated successfully.',
                // 'new_status' => $choosePlan->status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                't-success' => false,
                'message' => 'Choose Plan not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                't-success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

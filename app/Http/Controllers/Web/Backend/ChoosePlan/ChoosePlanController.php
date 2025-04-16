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
        $choosePlans = ChoosePlan::all(); // Make sure this is used
        return view('backend.layouts.choose_plan.create', compact('choosePlans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan' => 'required|in:free,monthly,yearly,lifetime',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|string',
            'touchpoint_limit' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value !== 'unlimited' && (!is_numeric($value) || $value < 0)) {
                        $fail('The ' . str_replace('_', ' ', $attribute) . ' must be a non-negative number or "unlimited".');
                    }
                },
            ],
            'has_ads' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // If plan already exists
        if (ChoosePlan::where('plan', $validated['plan'])->exists()) {
            return redirect()->back()
                ->withErrors(['plan' => 'This plan has already been added.'])
                ->withInput();
        }

        // Set touchpoint_limit based on the selection
        if ($validated['plan'] === 'free') {
            $validated['price'] = 0.00;
            $validated['touchpoint_limit'] = '15';  // Free plan's touchpoint limit is always 15
            $validated['icon'] = false;
        } else {
            // Handle unlimited or custom values
            $validated['touchpoint_limit'] = $validated['touchpoint_limit'] ?? 'unlimited';
            $validated['icon'] = true;  // Set icon for non-free plans
        }

        // Save the plan to the database
        $plan = new ChoosePlan();
        $plan->plan = $validated['plan'];
        $plan->price = $validated['price'];
        $plan->billing_cycle = $validated['billing_cycle'];
        $plan->touchpoint_limit = $validated['touchpoint_limit'];  // Save the selected limit (either unlimited or custom)
        $plan->has_ads = $validated['has_ads'];
        $plan->icon = $validated['icon'];
        $plan->save();

        return redirect()->route('choose.plan.index')
            ->with('success', 'Choose Plan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ChoosePlan $choosePlan, $id) {}

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

    public function update(Request $request, $id)
    {
        // Step 1: Validate with Validator
        $validator = Validator::make($request->all(), [
            'plan' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|string|in:monthly,yearly',
            'touchpoint_type' => 'required|in:unlimited,custom',
            'touchpoint_limit' => [
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->touchpoint_type === 'custom') {
                        if (!is_numeric($value) || $value < 0) {
                            $fail('The ' . str_replace('_', ' ', $attribute) . ' must be a non-negative number.');
                        }
                    }
                },
            ],
            'has_ads' => 'required|boolean',
        ]);

        // Step 2: Redirect if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Step 3: Extract validated input
        $validated = $validator->validated();

        // Step 4: Override `touchpoint_limit` based on dropdown selection
        $validated['touchpoint_limit'] = $validated['touchpoint_type'] === 'unlimited'
            ? 'unlimited'
            : $validated['touchpoint_limit'];

        // Step 5: Remove `touchpoint_type` (not saved to DB)
        unset($validated['touchpoint_type']);


        // Step 6: Update the plan
        // dd ($validated);
        $plan = ChoosePlan::findOrFail($id);
        $plan->plan = $validated['plan'];
        $plan->price = $validated['price'];
        $plan->billing_cycle = $validated['billing_cycle'];
        $plan->touchpoint_limit = $validated['touchpoint_limit'];
        $plan->has_ads = $validated['has_ads'];
        $plan->icon = $validated['plan'] === 'free' ? false : true;

        $plan->save();

        return redirect()->route('choose.plan.index')
            ->with('success', 'Choose Plan Update successfully.');
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

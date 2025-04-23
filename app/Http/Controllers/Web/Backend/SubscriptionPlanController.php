<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Plan;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class SubscriptionPlanController extends Controller {
    /**
     * Display a listing of the resource of subscription plans.
     *
     * @param Request $request
     * @return View|JsonResponse
     */
    public function index(Request $request): View | JsonResponse {
        try {
            if ($request->ajax()) {
                $data = Plan::get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('touch_points', fn($plan) =>
                        $plan->touch_points === null ? 'Unlimited' : $plan->touch_points
                    )
                    ->addColumn('status', function ($subscriptionPlan) {
                        $status = '<div class="form-check form-switch" style="margin-left: 40px; width: 50px; height: 24px;">';
                        $status .= '<input class="form-check-input" type="checkbox" role="switch" id="SwitchCheck' . $subscriptionPlan->id . '" ' . ($subscriptionPlan->status == 'active' ? 'checked' : '') . ' onclick="showStatusChangeAlert(' . $subscriptionPlan->id . ')">';
                        $status .= '</div>';
                        return $status;
                    })
                    ->addColumn('action', function ($subscriptionPlan) {
                        return '
                            <div class="hstack gap-3 fs-base">
                                <a href="javascript:void(0);" class="link-primary text-decoration-none edit-service" data-id="' . $subscriptionPlan->id . '" title="Edit">
                                    <i class="ri-pencil-line" style="font-size:24px;"></i>
                                </a>
                            </div>';
                    })
                    ->rawColumns(['touch_points', 'status', 'action'])
                    ->make();
            }
            return view('backend.layouts.subscription_plans.index');
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource of subscription plans.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse {
        $validator = Validator::make($request->all(), [
            'price'             => 'required|numeric',
            'billing_cycle'     => 'required|in:monthly,yearly,lifetime',
            'touch_points'      => 'nullable|integer|min:0',
            'has_ads'           => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        $subscriptionPlan = Plan::findOrFail($id);

        try {
            $subscriptionPlan->update([
                'price'             => $request->price,
                'billing_cycle'     => $request->billing_cycle,
                'touch_points'      => $request->touch_points,
                'has_ads'           => $request->has_ads,
            ]);

            return response()->json(['success' => true, 'message' => 'Subscription plan updated successfully.']);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the Subscription plan: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Change the status of the specified resource of subscription plans.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function status(int $id): JsonResponse {
        try {
            $subscriptionPlan = Plan::findOrFail($id);

            if ($subscriptionPlan->status == 'active') {
                $subscriptionPlan->status = 'inactive';
                $subscriptionPlan->save();

                return response()->json([
                    'success' => false,
                    'message' => 'Subscription plan unpublished successfully.',
                    'data'    => $subscriptionPlan,
                ]);
            } else {
                $subscriptionPlan->status = 'active';
                $subscriptionPlan->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Subscription plan published successfully.',
                    'data'    => $subscriptionPlan,
                ]);
            }
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}

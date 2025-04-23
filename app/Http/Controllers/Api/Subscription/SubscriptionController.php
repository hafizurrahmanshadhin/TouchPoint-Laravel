<?php

namespace App\Http\Controllers\Api\Subscription;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Subscription\SubscriptionPlanResource;
use App\Models\Plan;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller {
    public function index(): JsonResponse {
        try {
            $SubscriptionPlans = Plan::where('status', 'active')->orderBy('id', 'asc')->get();

            return Helper::jsonResponse(true, 'Subscription plans retrieved successfully.', 200,
                SubscriptionPlanResource::collection($SubscriptionPlans)
            );
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while fetching subscription plans.', 500, null,
                ['exception' => $e->getMessage()]
            );
        }
    }

    public function choose(Request $request): JsonResponse {
        // 1) Validate input
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:plans,id',
        ]);

        if ($validator->fails()) {
            return Helper::jsonResponse(false, 'Validation error.', 422, null, $validator->errors());
        }

        try {
            $user = auth()->user();

            // 2) Fetch the plan and ensure it's active
            $SubscriptionPlan = Plan::where('status', 'active')->findOrFail($request->plan_id);

            // 3) Cancel any existing active subscription
            UserSubscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->update([
                    'status'     => 'canceled',
                    'expires_at' => Carbon::now(),
                ]);

            // 4) Prepare dates for the new subscription
            $startsAt  = Carbon::now();
            $expiresAt = match ($SubscriptionPlan->billing_cycle) {
                'monthly' => $startsAt->clone()->addMonth(),
                'yearly' => $startsAt->clone()->addYear(),
                'lifetime' => null,
                default => null,
            };

            // 5) Create the new subscription
            $subscription = UserSubscription::create([
                'user_id'    => $user->id,
                'plan_id'    => $SubscriptionPlan->id,
                'starts_at'  => $startsAt,
                'expires_at' => $expiresAt,
                'status'     => 'active',
            ]);

            // 6) Return success with both subscription + plan info
            return Helper::jsonResponse(
                true,
                'Subscription plan added successfully.',
                201,
                [
                    'subscription' => [
                        'id'         => $subscription->id,
                        'starts_at'  => $subscription->starts_at->toIso8601String(),
                        'expires_at' => $subscription->expires_at?->toIso8601String(),
                        'status'     => $subscription->status,
                    ],
                    'plan'         => new SubscriptionPlanResource($SubscriptionPlan),
                ]
            );
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while choosing the plan.', 500, null,
                ['exception' => $e->getMessage()]
            );
        }
    }
}

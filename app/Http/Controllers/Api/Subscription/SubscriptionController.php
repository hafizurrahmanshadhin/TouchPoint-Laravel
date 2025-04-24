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
            $plans = Plan::where('status', 'active')->orderBy('id')->get();

            return Helper::jsonResponse(
                true,
                'Subscription plans retrieved successfully.',
                200,
                SubscriptionPlanResource::collection($plans)
            );
        } catch (Exception $e) {
            return Helper::jsonResponse(
                false,
                'An error occurred while fetching subscription plans.',
                500,
                null,
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
            $plan = Plan::where('status', 'active')->findOrFail($request->plan_id);

            // 3) Compute start + expiration dates
            $startsAt  = Carbon::now();
            $expiresAt = match ($plan->subscription_plan) {
                'monthly' => $startsAt->clone()->addMonth(),
                'yearly' => $startsAt->clone()->addYear(),
                'free', // free plan: never expires
                'lifetime' // lifetime plan: never expires
                => null,
            };

            // 4) Create or update the single subscription row for this user
            $subscription = UserSubscription::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'plan_id'    => $plan->id,
                    'starts_at'  => $startsAt,
                    'expires_at' => $expiresAt,
                    'status'     => 'active',
                ]
            );

            // 5) Determine HTTP status & message
            $statusCode = $subscription->wasRecentlyCreated ? 201 : 200;
            $message    = $subscription->wasRecentlyCreated
            ? 'Subscription plan added successfully.'
            : 'Subscription plan updated successfully.';

            // 6) Return payload
            return Helper::jsonResponse(
                true,
                $message,
                $statusCode,
                [
                    'subscription' => [
                        'id'         => $subscription->id,
                        'starts_at'  => $subscription->starts_at->toIso8601String(),
                        'expires_at' => $subscription->expires_at?->toIso8601String(),
                        'status'     => $subscription->status,
                    ],
                    'plan'         => new SubscriptionPlanResource($plan),
                ]
            );
        } catch (Exception $e) {
            return Helper::jsonResponse(
                false,
                'An error occurred while choosing the plan.',
                500,
                null,
                ['exception' => $e->getMessage()]
            );
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\TouchPoint\ListTouchPointResource;
use App\Models\TouchPoint;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller {
    /**
     * Show the list of all touch points for the authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function listTouchPoints(Request $request): JsonResponse {
        try {
            $user = $request->user();

            // Check subscription
            $subscription = $user->activeSubscription;
            if (!$subscription) {
                return Helper::jsonResponse(false, 'You must take a subscription plan before viewing touch-points.', 403);
            }

            // Get contact type from query for filtering
            $contactType = $request->query('contact_type');

            // Build base query for this user
            $query = TouchPoint::where('user_id', $user->id);

            // If contact_type is "personal" or "business," apply filter
            if (in_array($contactType, ['personal', 'business'])) {
                $query->where('contact_type', $contactType);
            }

            // Fetch and sort touch points
            $touchPoints = $query->get()->sortBy(function ($touchPoint) {
                $today     = Carbon::today();
                $targetDay = $touchPoint->touch_point_start_date;
                if ($targetDay->isPast() && !$targetDay->isToday()) {
                    return 1; // red
                } elseif ($targetDay->isToday()) {
                    return 2; // blue
                } elseif ($targetDay->isTomorrow()) {
                    return 3; // yellow
                }
                return 4; // green
            })->values();

            // Retrieve current active plan
            $activePlan = $user->activePlan;
            $rawPlan    = $activePlan ? $activePlan->subscription_plan : null;

            // Convert raw plan value to display label
            switch ($rawPlan) {
            case 'free':
                $planLabel = 'Free Plan';
                break;
            case 'monthly':
                $planLabel = 'Monthly Plan';
                break;
            case 'yearly':
                $planLabel = 'Yearly Plan';
                break;
            case 'lifetime':
                $planLabel = 'Lifetime Plan';
                break;
            default:
                $planLabel = null;
                break;
            }

            // Prepare user data
            $userData = [
                'id'                => $user->id,
                'name'              => $user->first_name . ' ' . $user->last_name,
                'avatar'            => $user->avatar ? asset($user->avatar) : null,
                'subscription_plan' => $planLabel,
            ];

            return response()->json([
                'status'  => true,
                'message' => 'Touch points list retrieved successfully.',
                'code'    => 200,
                'user'    => $userData,
                'data'    => ListTouchPointResource::collection($touchPoints),
            ], 200);

        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while fetching touch points.', 500, null, $e->getMessage());
        }
    }
}

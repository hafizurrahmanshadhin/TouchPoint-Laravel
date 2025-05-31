<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\TouchPoint\ListTouchPointResource;
use App\Http\Resources\Api\TouchPoint\ResetTouchPointResource;
use App\Http\Resources\Api\TouchPoint\ShowSpecificTouchPointDetailsResource;
use App\Models\TouchPoint;
use App\Notifications\BadgeEarned;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HomeController extends Controller {
    /**
     * Show the list of all touch points for the authenticated user.
     *
     * Supports optional filtering by contact_type (personal or business),
     * and sorts:
     *   1) Overdue (red) tasks by days overdue ascending
     *   2) Today   (blue)
     *   3) Tomorrow(yellow)
     *   4) Future  (green)
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function listTouchPoints(Request $request): JsonResponse {
        try {
            $currentUser = $request->user();

            // 1) Ensure the user has an active subscription
            if (!$currentUser->activeSubscription) {
                return Helper::jsonResponse(false, 'You must take a subscription plan before viewing touch-points.', 403);
            }

            // 2) Optionally filter by contact type (personal/business)
            $requestedContactType = $request->query('contact_type');
            $touchPointsQuery     = TouchPoint::where('user_id', $currentUser->id);

            if (in_array($requestedContactType, ['personal', 'business'], true)) {
                $touchPointsQuery->where('contact_type', $requestedContactType);
            }

            // 3) Retrieve the filtered collection
            $touchPointCollection = $touchPointsQuery->get();

            // 4) Define today for date comparisons
            $today = Carbon::today();

            // 5) Sort the collection with a custom comparator:
            //    - Primary: overdue (1), today (2), tomorrow (3), future (4)
            //    - Secondary (only for overdue): days overdue ascending
            $orderedTouchPoints = $touchPointCollection->sort(function ($first, $second) use ($today) {
                // Determine the group priority for each touch point
                $groupPriority = function (TouchPoint $touchPoint) use ($today) {
                    $startDate = $touchPoint->touch_point_start_date;
                    if ($startDate->isPast() && !$startDate->isToday()) {
                        return 1; // Overdue
                    }
                    if ($startDate->isToday()) {
                        return 2; // Today
                    }
                    if ($startDate->isTomorrow()) {
                        return 3; // Tomorrow
                    }
                    return 4; // Future
                };

                $firstGroup  = $groupPriority($first);
                $secondGroup = $groupPriority($second);

                // Primary sort by group priority
                if ($firstGroup !== $secondGroup) {
                    return $firstGroup < $secondGroup ? -1 : 1;
                }

                // Secondary sort for overdue group: days overdue ascending
                if ($firstGroup === 1) {
                    $daysOverdueFirst  = $first->touch_point_start_date->diffInDays($today);
                    $daysOverdueSecond = $second->touch_point_start_date->diffInDays($today);

                    if ($daysOverdueFirst !== $daysOverdueSecond) {
                        return $daysOverdueFirst < $daysOverdueSecond ? -1 : 1;
                    }
                }

                // Otherwise, preserve original order
                return 0;
            })->values(); // reindex the collection

            // 6) Build user info and friendly plan label
            $activePlan = $currentUser->activePlan;
            $planKey    = $activePlan ? $activePlan->subscription_plan : null;
            switch ($planKey) {
            case 'free':$planLabel = 'Free Plan';
                break;
            case 'monthly':$planLabel = 'Monthly Plan';
                break;
            case 'yearly':$planLabel = 'Yearly Plan';
                break;
            case 'lifetime':$planLabel = 'Lifetime Plan';
                break;
            default:$planLabel = null;
                break;
            }

            $userInfo = [
                'id'                => $currentUser->id,
                'name'              => "{$currentUser->first_name} {$currentUser->last_name}",
                'avatar'            => $currentUser->avatar ? asset($currentUser->avatar) : null,
                'subscription_plan' => $planLabel,
            ];

            // 7) Return the structured JSON
            return response()->json([
                'status'  => true,
                'message' => 'Touch points list retrieved successfully.',
                'code'    => 200,
                'user'    => $userInfo,
                'data'    => ListTouchPointResource::collection($orderedTouchPoints),
            ], 200);

        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while fetching touch points.', 500, null,
                ['exception' => $e->getMessage()]
            );
        }
    }

    /**
     * Show details of a specific touch point including its history.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function showSpecificTouchPointDetails(Request $request, int $id): JsonResponse {
        try {
            $user = $request->user();

            // Must have active subscription.
            if (!$user->activeSubscription) {
                return Helper::jsonResponse(false, 'You must take a subscription plan before viewing touch-point details', 403);
            }

            // Retrieve the touch point and ensure ownership
            $touchPoint = TouchPoint::where('user_id', $user->id)->findOrFail($id);

            // Build history for this touch-point only
            $history = [];
            if ($touchPoint->is_completed) {
                $history[] = [
                    'id'             => $touchPoint->id,
                    'contact_method' => $touchPoint->contact_method,
                    'day'            => $touchPoint->updated_at->diffForHumans(),
                ];
            }

            return Helper::jsonResponse(true, 'Specific touch point details retrieved successfully.', 200,
                [
                    'touch_point' => new ShowSpecificTouchPointDetailsResource($touchPoint),
                    'history'     => $history,
                ]
            );
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while retrieving the touch point.', 500, null,
                ['exception' => $e->getMessage()]
            );
        }
    }

    /**
     * Reset (Mark as Complete) a specific touch point.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function resetTouchPoint(Request $request, int $id): JsonResponse {
        try {
            // Validate the incoming 'contact_method'
            $validated = $request->validate([
                'contact_method' => ['required', Rule::in(['call', 'text', 'meetup'])],
            ]);

            $user = $request->user();
            if (!$user->activeSubscription) {
                return Helper::jsonResponse(false, 'You must have an active plan.', 403);
            }

            $tp = TouchPoint::where('user_id', $user->id)->findOrFail($id);

            // If this point is past the deadline, reset score to 0; otherwise increment
            $today = Carbon::today();
            if ($tp->touch_point_start_date->lt($today)) {
                $user->score = 0;
            } else {
                $user->score = ($user->score ?? 0) + 1;
            }
            $user->save();

            // Update the contact_method and mark as completed
            $tp->contact_method = $validated['contact_method'];
            if (!$tp->is_completed) {
                $tp->is_completed = true;
            }

            // Bump start_date based on frequency
            switch ($tp->frequency) {
            case 'daily':
                $tp->touch_point_start_date = $tp->touch_point_start_date->addDay();
                break;
            case 'weekly':
                $tp->touch_point_start_date = $tp->touch_point_start_date->addWeek();
                break;
            case 'monthly':
                $tp->touch_point_start_date = $tp->touch_point_start_date->addMonth();
                break;
            case 'custom':
                if ($tp->custom_days) {
                    $tp->touch_point_start_date = $tp->touch_point_start_date->addDays($tp->custom_days);
                }
                break;
            }
            $tp->save();

            $completedCount = TouchPoint::where('user_id', $user->id)->where('is_completed', true)->count();

            $targetCount = 5;

            // if ($completedCount >= $targetCount) {
            //     $newBadge = 'gold';
            // } elseif ($completedCount >= 3) {
            //     $newBadge = 'silver';
            // } else {
            //     $newBadge = 'bronze';
            // }

            // Update badge based on user->score
            if ($user->score >= $targetCount) {
                $newBadge = 'gold';
            } elseif ($user->score >= 3) {
                $newBadge = 'silver';
            } else {
                $newBadge = 'bronze';
            }

            if ($user->badge !== $newBadge) {
                $user->badge = $newBadge;
                $user->save();

                $user->notify(new BadgeEarned($newBadge));
            }

            $payload = [
                'badge'           => $user->badge,
                'completed_count' => min($completedCount, $targetCount),
                'target_count'    => $targetCount,
            ];

            return Helper::jsonResponse(true, 'Touch point marked complete; badge updated.', 200, new ResetTouchPointResource($payload));
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }
}

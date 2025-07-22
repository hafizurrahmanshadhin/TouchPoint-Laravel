<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Profile\CompletedTouchPointResource;
use App\Http\Resources\Api\Profile\ProfileResource;
use App\Http\Resources\Api\Profile\UpcomingTouchPointResource;
use App\Models\TouchPoint;
use App\Models\TouchPointHistory;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller {
    /**
     * Retrieve the authenticated user’s profile.
     *
     * @param Request  $request
     * @return JsonResponse
     */
    public function getProfile(Request $request): JsonResponse {
        try {
            $currentUser = $request->user();

            // Must have active subscription.
            if (!$currentUser->activeSubscription) {
                return Helper::jsonResponse(false, 'You must take a subscription plan before viewing your profile.', 403);
            }

            // Wrap the user model in a resource for consistent formatting
            return Helper::jsonResponse(true, 'Profile retrieved successfully.', 200, new ProfileResource($currentUser));
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Show all touch points that the user has marked completed. Completed touch points list.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function completedTouchPointsList(Request $request): JsonResponse {
        try {
            $currentUser = $request->user();

            // Ensure user has an active subscription
            if (!$currentUser->activeSubscription) {
                return Helper::jsonResponse(false, 'You must take a subscription plan before viewing completed touch-points.', 403);
            }

            // Fetch only completed touch points and sort by completion date (most recent first)
            $completedTouchPoints = $currentUser->touchPoints()->where('is_completed', true)->orderByDesc('updated_at')->get();

            return Helper::jsonResponse(true, 'Completed touch points retrieved successfully.', 200,
                CompletedTouchPointResource::collection($completedTouchPoints)
            );
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while fetching completed touch points.', 500, null,
                ['exception' => $e->getMessage()]
            );
        }
    }

    /**
     * Show all upcoming (future & not completed) touch points.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function upcomingTouchPointsList(Request $request): JsonResponse {
        try {
            $currentUser = $request->user();

            // Ensure user has an active subscription
            if (!$currentUser->activeSubscription) {
                return Helper::jsonResponse(false, 'You must take a subscription plan before viewing upcoming touch-points.', 403);
            }

            // Fetch only future & incomplete touch points, sorted by date ascending
            $today               = Carbon::today();
            $upcomingTouchPoints = $currentUser->touchPoints()
                ->where('is_completed', false)
                ->where('touch_point_start_date', '>', $today)
                ->orderBy('touch_point_start_date', 'asc')
                ->get();

            return Helper::jsonResponse(true, 'Upcoming touch points retrieved successfully.', 200,
                UpcomingTouchPointResource::collection($upcomingTouchPoints)
            );
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while fetching upcoming touch points.', 500, null,
                ['exception' => $e->getMessage()]
            );
        }
    }

    /**
     * Return touch-point activity for the current week (Sun–Sat).
     *
     * Each day includes:
     *   - day         (e.g. "Sun")
     *   - date        ("YYYY-MM-DD")
     *   - completed   (count where is_completed=true)
     *   - incomplete  (count where is_completed=false)
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function touchPointActivity(Request $request): JsonResponse {
        try {
            $user = $request->user();

            // Check if user has an active subscription
            if (!$user->activeSubscription) {
                return Helper::jsonResponse(false, 'You must take a subscription plan before viewing activity.', 403);
            }

            // Prepare today's date and start of the week (Sunday)
            $today     = Carbon::today();
            $weekStart = $today->copy()->startOfWeek(CarbonInterface::SUNDAY);

            // Initialize an empty array to store week's activity
            $activity = [];

            //  Loop through 7 days (Sunday to Saturday)
            for ($offset = 0; $offset < 7; $offset++) {
                // Get each day by adding offset to week's start
                $date = $weekStart->copy()->addDays($offset);

                // Fetch ONLY touch points that are actually scheduled for that specific day
                $scheduledTouchPoints = TouchPoint::where('user_id', $user->id)
                    ->whereDate('touch_point_start_date', $date->toDateString())
                    ->get();

                // Fetch completed touch points from history for that day
                $completedHistoryCount = TouchPointHistory::where('user_id', $user->id)
                    ->whereDate('completed_date', $date->toDateString())
                    ->count();

                // Calculate counts for scheduled touchpoints
                $scheduledTotalCount = $scheduledTouchPoints->count();

                // For future dates, don't count touchpoints as "completed" even if is_completed = true
                // because they haven't actually been completed yet on that future date
                if ($date->isFuture()) {
                    $scheduledCompletedCount  = 0; // Future touchpoints are never "completed" yet
                    $scheduledIncompleteCount = $scheduledTotalCount; // All scheduled future touchpoints are incomplete
                } else {
                    // For past/today dates, use the actual completion status
                    $scheduledCompletedCount  = $scheduledTouchPoints->where('is_completed', true)->count();
                    $scheduledIncompleteCount = $scheduledTotalCount - $scheduledCompletedCount;
                }

                // Total counts including history (only for past dates and today)
                if ($date->isFuture()) {
                    // Future dates: only show scheduled touchpoints, no history
                    $totalCount      = $scheduledTotalCount;
                    $completedCount  = 0; // No completions for future dates
                    $incompleteCount = $scheduledIncompleteCount;
                } else {
                    // Past/today dates: include both scheduled and historical
                    $totalCount      = $scheduledTotalCount + $completedHistoryCount;
                    $completedCount  = $scheduledCompletedCount + $completedHistoryCount;
                    $incompleteCount = $scheduledIncompleteCount;
                }

                // Prepare segments based on the activity status
                $segments = [];

                if ($totalCount == 0) {
                    // No touch points at all -> gray color
                    $segments = [
                        ['count' => 0, 'color' => 'gray'],
                    ];
                } elseif ($date->isToday()) {
                    // Today - Completed: white, Incomplete: blue
                    if ($completedCount > 0) {
                        $segments[] = ['count' => $completedCount, 'color' => 'white'];
                    }
                    if ($incompleteCount > 0) {
                        $segments[] = ['count' => $incompleteCount, 'color' => 'blue'];
                    }
                } elseif ($date->isTomorrow()) {
                    // Tomorrow's activities - yellow color (only incomplete, since nothing can be completed in future)
                    if ($incompleteCount > 0) {
                        $segments[] = ['count' => $incompleteCount, 'color' => 'yellow'];
                    }
                } elseif ($date->isFuture()) {
                    // Future days after tomorrow - green color (only incomplete)
                    if ($incompleteCount > 0) {
                        $segments[] = ['count' => $incompleteCount, 'color' => 'green'];
                    }
                } else {
                    // Past days - Completed: white, Incomplete: red
                    if ($completedCount > 0) {
                        $segments[] = ['count' => $completedCount, 'color' => 'white'];
                    }
                    if ($incompleteCount > 0) {
                        $segments[] = ['count' => $incompleteCount, 'color' => 'red'];
                    }
                }

                // Push data for each day
                $activity[] = [
                    'day'        => $date->format('D'),
                    'date'       => $date->toDateString(),
                    'total'      => $totalCount,
                    'completed'  => $completedCount,
                    'incomplete' => $incompleteCount,
                    'segments'   => $segments,
                ];
            }

            // Prepare today's date and day separately for the response
            $todayDate    = $today->format('m/d/Y');
            $todayWeekDay = $today->format('D');

            return response()->json([
                'status'   => true,
                'message'  => 'TouchPoint activity retrieved successfully.',
                'code'     => 200,
                'day'      => $todayDate,
                'week_day' => $todayWeekDay,
                'data'     => $activity,
            ]);

        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while fetching activity.', 500, null,
                ['exception' => $e->getMessage()]
            );
        }
    }
}

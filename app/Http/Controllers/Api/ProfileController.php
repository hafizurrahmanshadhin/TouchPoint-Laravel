<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Profile\CompletedTouchPointResource;
use App\Http\Resources\Api\Profile\ProfileResource;
use App\Http\Resources\Api\Profile\UpcomingTouchPointResource;
use Carbon\Carbon;
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
}

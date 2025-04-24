<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TouchPoint\CreateTouchPointRequest;
use App\Http\Resources\Api\TouchPoint\TouchPointResource;
use App\Models\TouchPoint;
use Exception;
use Illuminate\Http\JsonResponse;

class TouchPointController extends Controller {
    public function createTouchPoint(CreateTouchPointRequest $request): JsonResponse {
        $user = $request->user();

        // Must have an active subscription
        $subscription = $user->activeSubscription;
        if (!$subscription) {
            return Helper::jsonResponse(false, 'You must take a subscription plan before creating touch-points.', 403);
        }

        // Enforce plan's touch_points limit (null = unlimited)
        $limit = $subscription->plan->touch_points;
        if ($limit !== null) {
            $count = TouchPoint::where('user_id', $user->id)->count();
            if ($count >= $limit) {
                return Helper::jsonResponse(false, "Your plan allows only {$limit} touch-points. Please upgrade to add more.", 403);
            }
        }

        $data = $request->validated();

        // Prevent duplicate date+time
        $exists = TouchPoint::where('user_id', $user->id)
            ->where('touch_point_start_date', $data['touch_point_start_date'])
            ->where('touch_point_start_time', $data['touch_point_start_time'])
            ->exists();
        if ($exists) {
            return Helper::jsonResponse(false, 'You already have a touch-point scheduled at that exact date and time.', 422);
        }

        try {
            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $path = Helper::fileUpload($request->file('avatar'), 'touch_points/avatars', $data['name']);
                if ($path) {
                    $data['avatar'] = $path;
                }
            }

            // Null out custom_days if not custom frequency
            if ($data['frequency'] !== 'custom') {
                $data['custom_days'] = null;
            }

            $data['user_id'] = $user->id;
            $touchPoint      = TouchPoint::create($data);

            return Helper::jsonResponse(true, 'Touch point created successfully.', 201, new TouchPointResource($touchPoint));
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while creating the touch point.', 500, null,
                ['exception' => $e->getMessage()]
            );
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TouchPoint\CreateTouchPointRequest;
use App\Http\Requests\Api\TouchPoint\UpdateTouchPointRequest;
use App\Http\Resources\Api\TouchPoint\SummaryTouchPointResource;
use App\Http\Resources\Api\TouchPoint\TouchPointResource;
use App\Models\TouchPoint;
use App\Notifications\TouchPointAdded;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TouchPointController extends Controller {
    /**
     * Create a new touch point.
     *
     * @param CreateTouchPointRequest $request
     * @return JsonResponse
     */
    // public function createTouchPoint(CreateTouchPointRequest $request): JsonResponse {
    //     $user = $request->user();

    //     // Must have an active subscription
    //     $subscription = $user->activeSubscription;
    //     if (!$subscription) {
    //         return Helper::jsonResponse(false, 'You must take a subscription plan before creating touch-points.', 403);
    //     }

    //     // Enforce plan's touch_points limit (null = unlimited)
    //     $limit = $subscription->plan->touch_points;
    //     if ($limit !== null) {
    //         $count = TouchPoint::where('user_id', $user->id)->count();
    //         if ($count >= $limit) {
    //             return Helper::jsonResponse(false, "Your plan allows only {$limit} touch-points. Please upgrade to add more.", 403);
    //         }
    //     }

    //     $data = $request->validated();

    //     // Prevent duplicate date+time
    //     $exists = TouchPoint::where('user_id', $user->id)
    //         ->where('touch_point_start_date', $data['touch_point_start_date'])
    //         ->where('touch_point_start_time', $data['touch_point_start_time'])
    //         ->exists();
    //     if ($exists) {
    //         return Helper::jsonResponse(false, 'You already have a touch-point scheduled at that exact date and time.', 422);
    //     }

    //     try {
    //         // Handle avatar upload
    //         if ($request->hasFile('avatar')) {
    //             $path = Helper::fileUpload($request->file('avatar'), 'touch_points/avatars', $data['name']);
    //             if ($path) {
    //                 $data['avatar'] = $path;
    //             }
    //         }

    //         // Null out custom_days if not custom frequency
    //         if ($data['frequency'] !== 'custom') {
    //             $data['custom_days'] = null;
    //         }

    //         $data['user_id'] = $user->id;
    //         $touchPoint      = TouchPoint::create($data);

    //         $user->notify(new TouchPointAdded($touchPoint));

    //         return Helper::jsonResponse(true, 'Touch point created successfully.', 201, new TouchPointResource($touchPoint));
    //     } catch (Exception $e) {
    //         return Helper::jsonResponse(false, 'An error occurred while creating the touch point.', 500, null,
    //             ['exception' => $e->getMessage()]
    //         );
    //     }
    // }

    public function createTouchPoint(CreateTouchPointRequest $request): JsonResponse {
        Log::info('createTouchPoint invoked.');

        $user = $request->user();
        Log::info('User ID: ' . ($user ? $user->id : 'No user found'));

        // Must have an active subscription
        $subscription = $user->activeSubscription;
        if (!$subscription) {
            Log::warning('User has no active subscription, returning 403.');
            return Helper::jsonResponse(false, 'You must take a subscription plan before creating touch-points.', 403);
        }

        Log::info('Subscription found. Checking plan limits...');
        $limit = $subscription->plan->touch_points;
        Log::info('touch_points limit is: ' . ($limit === null ? 'null (unlimited)' : $limit));

        if ($limit !== null) {
            $count = TouchPoint::where('user_id', $user->id)->count();
            Log::info('Current touch-points count for user is: ' . $count);

            if ($count >= $limit) {
                Log::warning("User has reached the limit of {$limit} touch-points. Returning 403.");
                return Helper::jsonResponse(false, "Your plan allows only {$limit} touch-points. Please upgrade to add more.", 403);
            }
        }

        // Validate input data
        $data = $request->validated();
        Log::info('Validation passed, data:', $data);

        // Prevent duplicate date+time
        $exists = TouchPoint::where('user_id', $user->id)
            ->where('touch_point_start_date', $data['touch_point_start_date'])
            ->where('touch_point_start_time', $data['touch_point_start_time'])
            ->exists();
        Log::info('Duplicate check: ' . ($exists ? 'duplicate found' : 'no duplicate'));

        if ($exists) {
            Log::warning('Attempted to create duplicate date/time touch-point, returning 422.');
            return Helper::jsonResponse(false, 'You already have a touch-point scheduled at that exact date and time.', 422);
        }

        try {
            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                Log::info('Avatar file detected, proceeding with upload.');
                $path = Helper::fileUpload($request->file('avatar'), 'touch_points/avatars', $data['name']);
                if ($path) {
                    $data['avatar'] = $path;
                    Log::info('Avatar uploaded successfully: ' . $path);
                } else {
                    Log::info('Avatar upload returned null or failed.');
                }
            } else {
                Log::info('No avatar file present in the request.');
            }

            // Null out custom_days if not custom frequency
            if ($data['frequency'] !== 'custom') {
                Log::info('Frequency is not custom, setting custom_days to null.');
                $data['custom_days'] = null;
            } else {
                Log::info('Frequency is custom, custom_days = ' . (isset($data['custom_days']) ? $data['custom_days'] : 'not set'));
            }

            // Assign user ID
            $data['user_id'] = $user->id;
            Log::info('Attempting to create touchPoint record with data:', $data);

            $touchPoint = TouchPoint::create($data);
            Log::info('TouchPoint created successfully, ID: ' . $touchPoint->id);

            // Send in-app notification
            Log::info('Sending TouchPointAdded notification to user ID: ' . $user->id);
            $user->notify(new TouchPointAdded($touchPoint));
            Log::info('Notification sent.');

            Log::info('Everything succeeded, returning successful response.');
            return Helper::jsonResponse(true, 'Touch point created successfully.', 201, new TouchPointResource($touchPoint));
        } catch (Exception $e) {
            Log::error('Error occurred while creating the touch point: ' . $e->getMessage());
            return Helper::jsonResponse(false, 'An error occurred while creating the touch point.', 500, null, [
                'exception' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the summary of a specific touch point.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function summaryTouchPoint(Request $request, int $id): JsonResponse {
        try {
            $user = $request->user();

            // Must have active subscription.
            $subscription = $user->activeSubscription;
            if (!$subscription) {
                return Helper::jsonResponse(false, 'You must take a subscription plan before viewing touch-points.', 403);
            }

            // Retrieve the touch point and ensure ownership
            $touchPoint = TouchPoint::where('user_id', $user->id)->findOrFail($id);

            return Helper::jsonResponse(true, 'Touch point summary retrieved successfully.', 200, new SummaryTouchPointResource($touchPoint));
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while retrieving the touch point.', 500, null, [
                'exception' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update an existing touch point.
     *
     * @param UpdateTouchPointRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function updateTouchPoint(UpdateTouchPointRequest $request, int $id): JsonResponse {
        $user = $request->user();

        // Must have active subscription
        $subscription = $user->activeSubscription;
        if (!$subscription) {
            return Helper::jsonResponse(false, 'You must take a subscription plan before editing touch-points.', 403);
        }

        // Retrieve the touch point and ensure ownership
        $touchPoint = TouchPoint::where('user_id', $user->id)->findOrFail($id);

        // Prevent duplicate date+time for any other record
        if (
            $request->has('touch_point_start_date') &&
            $request->has('touch_point_start_time')
        ) {
            $exists = TouchPoint::where('user_id', $user->id)
                ->where('id', '!=', $touchPoint->id)
                ->where('touch_point_start_date', $request->input('touch_point_start_date'))
                ->where('touch_point_start_time', $request->input('touch_point_start_time'))
                ->exists();
            if ($exists) {
                return Helper::jsonResponse(false, 'You already have a touch-point at that date and time.', 422);
            }
        }

        try {
            $data = $request->validated();

            // Handle avatar upload if new file
            if ($request->hasFile('avatar')) {
                // Delete the old avatar if exists
                if ($touchPoint->avatar) {
                    Helper::fileDelete(public_path($touchPoint->avatar));
                }

                // Upload new avatar
                $path = Helper::fileUpload($request->file('avatar'), 'touch_points/avatars', $touchPoint->name);
                if ($path) {
                    $data['avatar'] = $path;
                }
            }

            // Null out custom_days if frequency changed or not 'custom'
            if (isset($data['frequency']) && $data['frequency'] !== 'custom') {
                $data['custom_days'] = null;
            }

            $touchPoint->update($data);

            return Helper::jsonResponse(true, 'Touch point updated successfully.', 200, new TouchPointResource($touchPoint));
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while updating the touch point.', 500, null, [
                'exception' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Delete a specific touch point.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function deleteTouchPoint(Request $request, int $id): JsonResponse {
        $user = $request->user();

        // Must have an active subscription
        if (!$user->activeSubscription) {
            return Helper::jsonResponse(false, 'You must have an active plan before deleting touch-points.', 403);
        }

        // Find the touch point belonging to this user (authorization)
        $touchPoint = TouchPoint::where('user_id', $user->id)->findOrFail($id);

        try {
            // Soft-delete the record
            $touchPoint->delete();

            return Helper::jsonResponse(true, 'Touch point deleted successfully.', 200);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred while deleting the touch point.', 500, null,
                ['exception' => $e->getMessage()]
            );
        }
    }
}

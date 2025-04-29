<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\FirebaseTokenResource;
use App\Models\FirebaseToken;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FirebaseTokenController extends Controller {
    /**
     * Store a new firebase token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function StoreFirebaseToken(Request $request): JsonResponse {
        try {
            $userId = Auth::id();

            $validator = Validator::make($request->all(), [
                'token'     => 'required|string',
                'device_id' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return Helper::jsonResponse(false, $validator->errors()->first(), 400);
            }

            //! Update or create token for the authenticated user
            $token = FirebaseToken::updateOrCreate(
                [
                    'user_id'   => $userId,
                    'device_id' => $request->device_id,
                ],
                [
                    'token' => $request->token,
                ]
            );

            return Helper::jsonResponse(true, 'Token saved successfully', 200, new FirebaseTokenResource($token));
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to store Firebase token', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get a firebase token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function GetFirebaseToken(Request $request): JsonResponse {
        try {
            $userId = Auth::id();

            $validator = Validator::make($request->all(), [
                'device_id' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return Helper::jsonResponse(false, $validator->errors()->first(), 400);
            }

            //! Retrieve the token based on the authenticated user and device ID
            $data = FirebaseToken::where('user_id', $userId)
                ->orWhere('device_id', $request->device_id)
                ->first();

            if (!$data) {
                return Helper::jsonResponse(true, 'No records found', 200, []);
            }

            return Helper::jsonResponse(true, 'Token fetched successfully', 200, new FirebaseTokenResource($data));
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to fetch Firebase token', 500, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Delete a firebase token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function DeleteFirebaseToken(Request $request): JsonResponse {
        try {
            $userId = Auth::id();

            $validator = Validator::make($request->all(), [
                'device_id' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return Helper::jsonResponse(false, $validator->errors()->first(), 400);
            }

            if ($request->has('device_id')) {
                //! If device_id is provided, delete the token for that specific device
                $token = FirebaseToken::where('user_id', $userId)
                    ->where('device_id', $request->device_id)
                    ->first();

                if ($token) {
                    $token->delete();
                    return Helper::jsonResponse(true, 'Token for the specified device deleted successfully', 200);
                }

                return Helper::jsonResponse(false, 'No records found for the specified device', 404);
            } else {
                //! If no device_id is provided, delete all tokens for the user
                $tokens = FirebaseToken::where('user_id', $userId)->get();

                if ($tokens->isNotEmpty()) {
                    foreach ($tokens as $token) {
                        $token->delete();
                    }
                    return Helper::jsonResponse(true, 'All tokens for the user deleted successfully', 200);
                }

                return Helper::jsonResponse(false, 'No tokens found for the user', 404);
            }
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to delete Firebase token(s)', 500, ['error' => $e->getMessage()]);
        }
    }
}

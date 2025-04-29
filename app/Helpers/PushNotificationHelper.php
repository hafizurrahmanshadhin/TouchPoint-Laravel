<?php

namespace App\Helpers;

use App\Models\FirebaseToken;
use Exception;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class PushNotificationHelper {
    /**
     * Send a FCM push to all tokens for a given user.
     * Logs each step for debugging when no device is available.
     */
    public static function sendPushNotification(int $userId, string $title, string $body): void {
        Log::info("[PushNotification] Preparing to send to user {$userId}");

        try {
            // Initialize Firebase messaging
            $factory = (new Factory)->withServiceAccount(storage_path('app/tbriggs-1fb7a-firebase-adminsdk-fbsvc-d3bedb22ff.json'));
            $messaging = $factory->createMessaging();

            // Retrieve tokens
            $tokens = FirebaseToken::where('user_id', $userId)->pluck('token')->toArray();
            Log::info("[PushNotification] Found tokens for user {$userId}", ['tokens' => $tokens]);

            if (empty($tokens)) {
                Log::warning("[PushNotification] No FCM tokens for user {$userId}, skipping push.");
                return;
            }

            // Build notification payload
            $notification = FirebaseNotification::create($title, $body);
            $message      = CloudMessage::new ()->withNotification($notification);
            Log::info("[PushNotification] Payload ready for user {$userId}", ['title' => $title, 'body' => $body]);

            // Send to multiple tokens
            $report = $messaging->sendMulticast($message, $tokens);

            // Log successes/failures
            Log::info("[PushNotification] Push report for user {$userId}", [
                'success_count' => $report->successes()->count(),
                'failure_count' => $report->failures()->count(),
            ]);

        } catch (Exception $e) {
            Log::error("[PushNotification] Exception sending to user {$userId}: {$e->getMessage()}");
        }
    }
}

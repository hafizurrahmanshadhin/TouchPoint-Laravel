<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller {
    public function index(Request $req): JsonResponse {
        try {
            $user = $req->user();

            // load all unread or recent notifications
            $all = $user->notifications()->orderBy('created_at', 'desc')->get();

            // group into Today / Yesterday / Earlier
            $grouped = [
                'today'     => [],
                'yesterday' => [],
                'earlier'   => [],
            ];

            foreach ($all as $n) {
                $when    = $n->created_at;
                $payload = [
                    'title'   => $n->data['title'],
                    'message' => $n->data['message'],
                    'time'    => $when->isToday()
                    ? $when->diffForHumans()
                    : ($when->isYesterday()
                        ? 'Yesterday'
                        : $when->format('M d, Y')),
                    'type'    => $n->data['type'],
                    'data'    => $n->data['data'],
                ];

                if ($when->isToday()) {
                    $grouped['today'][] = $payload;
                } elseif ($when->isYesterday()) {
                    $grouped['yesterday'][] = $payload;
                } else {
                    $grouped['earlier'][] = $payload;
                }

            }

            return Helper::jsonResponse(
                true,
                'Notifications retrieved.',
                200,
                $grouped
            );
        } catch (Exception $e) {
            return Helper::jsonResponse(
                false,
                'Failed to fetch notifications.',
                500,
                null,
                ['exception' => $e->getMessage()]
            );
        }
    }
}

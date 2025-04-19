<?php

namespace App\Http\Controllers\Api\Notification;

use Exception;

use App\Helpers\Helper;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{


    /**
     * Retrieve notifications for the authenticated user.
     *
     * @return JsonResponse
     */
    public function getNotifications(): JsonResponse
    {
        try {
            $user = auth()->user();

            $notifications = $user->notifications()->latest()->get();

            return Helper::jsonResponse(true, 'Notifications retrieved successfully', 200, $notifications);
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }
    }



    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        //
    }
}

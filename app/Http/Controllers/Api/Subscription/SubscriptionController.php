<?php

namespace App\Http\Controllers\Api\Subscription;

use Google\Rpc\Help;

use App\Helpers\Helper;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Subscription\SubscriptionResource;

class SubscriptionController extends Controller
{
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
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'choose_plan_id' => 'required|exists:choose_plans,id',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $subscription = Subscription::create($validated);

        if ($subscription) {
            return Helper::jsonResponse(
                true,
                'Subscription created successfully',
                201,
                // subscription::collection($subscription)
                $subscription
            );
        } else {
            return Helper::jsonResponse(
                false,
                'Failed to create subscription',
                500,
                []
            );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscription $subscription, $id)
    {
        // $subscription = Subscription::find($id);

        // dd($subscription);

        // if (!$subscription) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Subscription not found',
        //     ], 404);
        // }

        // return new SubscriptionResource($subscription);


        // $subscription = Subscription::with('choosePlan')->find($id);

        // dd($subscription);
        // if (!$subscription) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Subscription not found',
        //     ], 404);
        // }
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Subscription retrieved successfully',
        //     'data' => new SubscriptionResource($subscription),
        // ], 200);
        // return response()->json([
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subscription $subscription)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subscription $subscription)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription)
    {
        //
    }
}

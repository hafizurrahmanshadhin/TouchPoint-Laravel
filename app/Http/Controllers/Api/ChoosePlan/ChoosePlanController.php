<?php

namespace App\Http\Controllers\Api\ChoosePlan;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChoosePlan\ChoosePlanRequest;
use App\Http\Resources\Api\ChoosePlan\ChoosePlanResource;
use App\Models\ChoosePlan;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class ChoosePlanController extends Controller
{
    public function index(Request $request)
    {
        // $user = Auth::user();
        // $choosePlans = ChoosePlan::where('user_id', $user->id)->get();
        $choosePlans = ChoosePlan::all();
        if ($choosePlans->isEmpty()) {
            Log::info('No Choose Plans found for user: ' . Auth::id());
            return response()->json([
                'status' => false,
                'message' => 'No Choose Plans found',
                'data' => [],
            ]);
        }
        // Log::info('Choose Plans fetched successfully for user: ' . Auth::id(), ['choosePlans' => $choosePlans]);
        if ($choosePlans->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No Choose Plans found',
                'data' => [],
            ]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Choose Plans fetched successfully',
            'code' => 200,
            'data' => ChoosePlanResource::collection($choosePlans),
        ]);
    }

}
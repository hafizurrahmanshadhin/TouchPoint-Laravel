<?php

namespace App\Http\Controllers\Api\ChoosePlan;

use App\Helpers\Helper;
use App\Models\ChoosePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ChoosePlan\ChoosePlanRequest;
use App\Http\Resources\Api\ChoosePlan\ChoosePlanResource;


class ChoosePlanController extends Controller
{
    public function index(Request $request)
    {
        // $user = Auth::user();
        // $choosePlans = ChoosePlan::where('user_id', $user->id)->get();
        $choosePlans = ChoosePlan::where('status', 'active')->get();
        if ($choosePlans->isEmpty()) {
            Log::info('No Choose Plans found for user: ' . Auth::id());

            return Helper::jsonResponse(
                false,
                'No Choose Plans found',
                404,
                []
            );
        }
        // Log::info('Choose Plans fetched successfully for user: ' . Auth::id(), ['choosePlans' => $choosePlans]);
        if ($choosePlans->isEmpty()) {

            return Helper::jsonResponse(
                false,
                'No Choose Plans found',
                404,
                []
            );
        }
        return Helper::jsonResponse(
            true,
            'Choose Plans fetched successfully',
            200,
            ChoosePlanResource::collection($choosePlans)
        );
    }


    public function show(Request $request, $id){

        // $choosePlan = ChoosePlan::find($id);
        $choosePlan = ChoosePlan::where('id', $id)->where('status', 'active')->first();
        if (!$choosePlan) {

            return Helper::jsonResponse(
                false,
                'Choose Plan not found',
                404,
                []
            );
        }

        return Helper::jsonResponse(
            true,
            'Choose Plan fetched successfully',
            200,
            new ChoosePlanResource($choosePlan)
        );

    }

    public function list(Request $request)
    {
        $numberOfchoosePlan = $request->input('details');
    
        $choosePlan = ChoosePlan::where('status', 'active')
            ->latest()
            ->take($numberOfchoosePlan)
            ->get();
    
        if ($choosePlan->isEmpty()) {
            return Helper::jsonResponse(
                false,
                'No choosePlan found',
                404,
                []
            );
        }
    
        return Helper::jsonResponse(
            true,
            'choosePlan fetched successfully',
            200,
            ChoosePlanResource::collection($choosePlan) 
        );
    }
    

}
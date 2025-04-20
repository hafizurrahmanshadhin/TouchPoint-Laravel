<?php

namespace App\Http\Controllers\Api\AddTouchpoint;

use App\Helpers\Helper;

use Illuminate\Http\Request;
use App\Models\AddTouchpoint;
use App\Models\Contact;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\AddTouchpoint\AddTouchpointResource;
use Illuminate\Support\Facades\Log;
use Google\Rpc\Help;

class AddTouchpointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $numberOfAddTouchpoint = $request->input('details');
        Log::info('[DEBUG] Requested details numberOfAddTouchpoint:', ['numberOfAddTouchpoint' => $numberOfAddTouchpoint]);

        $addTouchpoints = AddTouchpoint::with('user')
            ->where('status', 'active')
            ->latest()
            ->take($numberOfAddTouchpoint)
            ->get();


            Log::info('[DEBUG] Requested details addTouchpoints:', ['addTouchpoints' => $addTouchpoints]);

        if ($addTouchpoints->isEmpty()) {
            return Helper::jsonResponse(
                false,
                'No AddTouchpoint found',
                404,
                []
            );
        }

        return Helper::jsonResponse(
            true,
            'AddTouchpoint fetched successfully',
            200,
            AddTouchpointResource::collection($addTouchpoints)
        );
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
        $request->validate([
            'user_id' => 'required|exists:users,id', // ← changed from contacts to users
            'number' => 'required|numeric|min:0',
            'contact_type' => 'required|in:personal,business',
            'contact_method' => 'required|in:call,text,meetup',
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'cadence' => 'required|in:daily,weekly,monthly,custom',
            'notes' => 'nullable|string'
        ]);

        $addTouchpoint = AddTouchpoint::create($request->all());

        if (!$addTouchpoint) {

            return Helper::jsonResponse(
                false,
                'Add Touchpoint creation failed',
                500,
                []
            );
        }
        return Helper::jsonResponse(
            true,
            'Add Touchpoint created successfully',
            201,
            new AddTouchpointResource($addTouchpoint)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $addTouchpoint = AddTouchpoint::with('user')
            ->where('id', $id)
            ->first();

        if (!$addTouchpoint) {
            return Helper::jsonResponse(
                false,
                'No AddTouchpoint found',
                404,
                []
            );
        }

        return Helper::jsonResponse(
            true,
            'AddTouchpoint fetched successfully',
            200,
            new AddTouchpointResource($addTouchpoint)
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AddTouchpoint $addTouchpoint)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate input
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'number' => 'required|numeric|min:0',
            'contact_type' => 'required|in:personal,business',
            'contact_method' => 'required|in:call,text,meetup',
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'cadence' => 'required|in:daily,weekly,monthly,custom',
            'notes' => 'nullable|string'
        ]);
    
        // Find the record by ID
        $addTouchpoint = AddTouchpoint::find($id);
    
        if (!$addTouchpoint) {
            return Helper::jsonResponse(
                false,
                'Add Touchpoint not found',
                404,
                []
            );
        }
    
        // Update the record
        $addTouchpoint->update($request->all());
    
        return Helper::jsonResponse(
            true,
            'Add Touchpoint updated successfully',
            200,
            new AddTouchpointResource($addTouchpoint)
        );
    }
    


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $addTouchpoint = AddTouchpoint::find($id);

        if (!$addTouchpoint) {
            return Helper::jsonResponse(
                false,
                'AddTouchpoint not found',
                404,
                []
            );
        }

        $addTouchpoint->delete();

        return Helper::jsonResponse(
            true,
            'AddTouchpoint deleted successfully',
            200,
            []
        );
    }
}

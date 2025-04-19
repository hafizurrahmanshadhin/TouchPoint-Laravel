<?php

namespace App\Http\Controllers\Api\AddTouchpoint;
use App\Helpers\Helper;

use Illuminate\Http\Request;
use App\Models\AddTouchpoint;
use App\Models\Contact;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\AddTouchpoint\AddTouchpointResource;
use Google\Rpc\Help;

class AddTouchpointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $numberOfAddTouchpoint = $request->input('details');
    
        $addTouchpoints = AddTouchpoint::with('contact')
            ->where('status', 'active')
            ->latest()
            ->take($numberOfAddTouchpoint)
            ->get();
    
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
            'contact_id' => 'required|exists:contacts,id',
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
        $addTouchpoint = AddTouchpoint::with('contact')
            ->where('id', $id)
            ->where('status', 'active')
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
        $addTouchpoint = AddTouchpoint::find($id);
    
        if (!$addTouchpoint) {
            return Helper::jsonResponse(
                false,
                'AddTouchpoint not found',
                404,
                []
            );
        }
    
        $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'contact_type' => 'required|in:personal,business',
            'contact_method' => 'required|in:call,text,meetup',
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'cadence' => 'required|in:daily,weekly,monthly,custom',
            'notes' => 'nullable|string',
        ]);
    
        // Update the fields
        $addTouchpoint->update($request->only([
            'contact_id',
            'contact_type',
            'contact_method',
            'start_date',
            'start_time',
            'cadence',
            'notes'
        ]));
    
        // Load the contact relationship (if you want to return it)
        $addTouchpoint->load('contact');
    
        return Helper::jsonResponse(
            true,
            'AddTouchpoint updated successfully',
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

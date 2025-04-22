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
    // public function index(Request $request)
    // {
    //     $numberOfAddTouchpoint = $request->input('details');

    //     $addTouchpoints = AddTouchpoint::where('status', 'active')
    //         ->latest()
    //         ->take($numberOfAddTouchpoint)
    //         ->get();


    //     if ($addTouchpoints->isEmpty()) {
    //         return Helper::jsonResponse(
    //             false,
    //             'No AddTouchpoint found',
    //             404,
    //             []
    //         );
    //     }

    //     return Helper::jsonResponse(
    //         true,
    //         'AddTouchpoint fetched successfully',
    //         200,
    //         AddTouchpointResource::collection($addTouchpoints)
    //     );
    // }



    public function index(Request $request)
    {
        $numberOfAddTouchpoint = $request->input('details');
        $contactType = $request->input('contact_type'); // 👈 Get the contact type

        $query = AddTouchpoint::where('status', 'active');

        // 👇 Only filter if contactType is specified and not "all"
        if ($contactType && in_array($contactType, ['personal', 'business'])) {
            $query->where('contact_type', $contactType);
        }

        $addTouchpoints = $query->latest()
            ->take($numberOfAddTouchpoint)
            ->get();

        if ($addTouchpoints->isEmpty()) {
            return Helper::jsonResponse(false, 'No AddTouchpoint found', 404, []);
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
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'user_id' => 'required|exists:users,id', // ← changed from contacts to users
    //         'number' => 'required|numeric|min:0',
    //         'contact_type' => 'required|in:personal,business',
    //         'contact_method' => 'required|in:call,text,meetup',
    //         'start_date' => 'required|date',
    //         'start_time' => 'required|date_format:H:i',
    //         'cadence' => 'required|in:daily,weekly,monthly,custom',
    //         'custom_cadence' => 'nullable|string',
    //         'notes' => 'nullable|string'
    //     ]);

    //     // Only include custom_cadence if cadence === 'custom'
    //     if ($request->cadence === 'custom') {
    //         $data['custom_cadence'] = (string) $request->custom_cadence;
    //     } else {
    //         $data['custom_cadence'] = null;
    //     }

    //     $addTouchpoint = AddTouchpoint::create($data);

    //     if (!$addTouchpoint) {

    //         return Helper::jsonResponse(
    //             false,
    //             'Add Touchpoint creation failed',
    //             500,
    //             []
    //         );
    //     }
    //     return Helper::jsonResponse(
    //         true,
    //         'Add Touchpoint created successfully',
    //         201,
    //         new AddTouchpointResource($addTouchpoint)
    //     );
    // }

    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|numeric|min:0',
            'contact_type' => 'required|in:personal,business',
            'contact_method' => 'required|in:call,text,meetup',
            'start_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'cadence' => 'required|in:daily,weekly,monthly,custom',
            'custom_cadence' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $data = $request->only([
            'number',
            'contact_type',
            'contact_method',
            'start_date',
            'start_time',
            'cadence',
            'notes'
        ]);

        // Only include custom_cadence if cadence === 'custom'
        if ($request->cadence === 'custom') {
            $data['custom_cadence'] = (string) $request->custom_cadence;
        } else {
            $data['custom_cadence'] = null;
        }

        $addTouchpoint = AddTouchpoint::create($data);

        if (!$addTouchpoint) {
            return Helper::jsonResponse(false, 'Add Touchpoint creation failed', 500, []);
        }

        return Helper::jsonResponse(true, 'Add Touchpoint created successfully', 201, new AddTouchpointResource($addTouchpoint));
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
    // public function update(Request $request, $id)
    // {
    //     // Validate input
    //     $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'number' => 'required|numeric|min:0',
    //         'contact_type' => 'required|in:personal,business',
    //         'contact_method' => 'required|in:call,text,meetup',
    //         'start_date' => 'required|date',
    //         'start_time' => 'required|date_format:H:i',
    //         'cadence' => 'required|in:daily,weekly,monthly,custom',
    //         'notes' => 'nullable|string'
    //     ]);

    //     // Find the record by ID
    //     $addTouchpoint = AddTouchpoint::find($id);

    //     if (!$addTouchpoint) {
    //         return Helper::jsonResponse(
    //             false,
    //             'Add Touchpoint not found',
    //             404,
    //             []
    //         );
    //     }

    //     // Update the record
    //     $addTouchpoint->update($request->all());

    //     return Helper::jsonResponse(
    //         true,
    //         'Add Touchpoint updated successfully',
    //         200,
    //         new AddTouchpointResource($addTouchpoint)
    //     );
    // }




    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'number' => 'required|numeric|min:0',
            'contact_type' => 'required|in:personal,business',
            'contact_method' => 'required|in:call,text,meetup',
            'start_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'cadence' => 'required|in:daily,weekly,monthly,custom',
            'custom_cadence' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'in:active,inactive',
            'touchpoints' => 'nullable|in:completed,upcoming',
        ]);

        $touchpoint = AddTouchpoint::find($id);

        if (!$touchpoint) {
            return Helper::jsonResponse(false, 'Touchpoint not found', 404);
        }

        $data = $request->only([
            'user_id',
            'number',
            'contact_type',
            'contact_method',
            'start_date',
            'start_time',
            'cadence',
            'notes',
            'status',
            'touchpoints'
        ]);

        if ($request->cadence === 'custom') {
            $data['custom_cadence'] = (string) $request->custom_cadence;
        } else {
            $data['custom_cadence'] = null;
        }

        $touchpoint->update($data);

        return Helper::jsonResponse(true, 'Touchpoint updated successfully', 200, new AddTouchpointResource($touchpoint));
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

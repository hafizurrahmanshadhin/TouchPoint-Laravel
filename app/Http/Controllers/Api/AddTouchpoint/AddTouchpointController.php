<?php

namespace App\Http\Controllers\Api\AddTouchpoint;
use App\Helpers\Helper;

use Illuminate\Http\Request;
use App\Models\AddTouchpoint;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\AddTouchpoint\AddTouchpointResource;

class AddTouchpointController extends Controller
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
    public function show(AddTouchpoint $addTouchpoint)
    {
        //
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
    public function update(Request $request, AddTouchpoint $addTouchpoint)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AddTouchpoint $addTouchpoint)
    {
        //
    }
}

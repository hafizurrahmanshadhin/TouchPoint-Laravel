<?php

namespace App\Http\Controllers\Api\DeviceToken;

use App\Http\Controllers\Controller;

use App\Models\device_token;
use Illuminate\Http\Request;

class DeviceTokenController extends Controller
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
    public function store(Request $request) {
        
        $request->validate(['token'=>'required|string','platform'=>'required|string']);
        $request->user()->deviceTokens()->updateOrCreate(
           ['token'=>$request->token],
           ['platform'=>$request->platform]
        );
        return response()->noContent();
     }

    /**
     * Display the specified resource.
     */
    public function show(device_token $device_token)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(device_token $device_token)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, device_token $device_token)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(device_token $device_token)
    {
        //
    }
}

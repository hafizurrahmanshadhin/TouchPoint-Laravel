<?php

namespace App\Http\Controllers\Api\Contact;

use Exception;

use Google\Rpc\Help;
use App\Helpers\Helper;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Contact\ContactResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $numberOfContacts = request()->input('details');

        $contacts = Contact::where('status', 'active')
        ->latest()
        ->take($numberOfContacts)
        ->get();

        if ($contacts->isEmpty()) {
            return Helper::jsonResponse(
                false,
                'No contacts found',
                404,
                []
            );
        }
        return Helper::jsonResponse(
            true,
            'Contacts fetched successfully',
            200,
            ContactResource::collection($contacts)
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
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:12|unique:contacts,phone',

        ]);

        $contact = Contact::create($request->all());

        if (!$contact) {
            return Helper::jsonResponse(
                false,
                'Contact creation failed',
                500,
                []
            );
        }

        return Helper::jsonResponse(
            true,
            'Contact created successfully',
            201,
            new ContactResource($contact)
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact,$id)
    {

        $contact = Contact::where('id', $id)
            ->orWhere('phone', $id)
            ->orWhere('name', $id)
            ->first();

        if (!$contact) {
            return Helper::jsonResponse(
                false,
                'Contact not found',
                404,
                []
            );
        }

        return Helper::jsonResponse(
            true,
            'Contact fetched successfully',
            200,
            new ContactResource($contact)
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact,$id)
    {   
        // deleted for pasafic data

        try {
            $contact = Contact::findOrFail($id);
    
            $contact->delete();
    
            return Helper::jsonResponse(
                true,
                'Contact deleted successfully',
                200,
                []
            );
        } catch (ModelNotFoundException $e) {
            return Helper::jsonResponse(
                false,
                'Contact not found',
                404,
                []
            );
        } catch (Exception $e) {
            return Helper::jsonResponse(
                false,
                'Something went wrong while deleting the contact',
                500,
                ['error' => $e->getMessage()]
            );
        }


        
    }
}

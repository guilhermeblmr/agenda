<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;

class ContactController extends Controller
{
    public function index()
    {
        return Contact::all();
    }

    public function store(StoreContactRequest  $request)
    {
        return Contact::create($request->validated());
    }

    public function update(UpdateContactRequest $request, $id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update($request->validated());
        return $contact;
    }

    public function destroy($id)
    {
        Contact::destroy($id);
        return response()->noContent();
    }
}

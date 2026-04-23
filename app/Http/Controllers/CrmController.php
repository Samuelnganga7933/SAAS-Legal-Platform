<?php

namespace App\Http\Controllers;

use App\Models\CrmContact;
use App\Models\CrmInteraction;
use Illuminate\Http\Request;

class CrmController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()?->isCeo()) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index()
    {
        return view('crm.index');
    }

    public function show(CrmContact $contact)
    {
        return view('crm.show', ['contact' => $contact]);
    }

    public function edit(CrmContact $contact)
    {
        return view('crm.edit', ['contact' => $contact]);
    }

    public function update(Request $request, CrmContact $contact)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'source' => 'required|in:direct,referral,ads,website,other',
            'status' => 'required|in:lead,prospect,client,inactive',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $contact->update($validated);

        return redirect()->route('crm.show', $contact)->with('success', 'Contact updated');
    }

    public function logInteraction(Request $request, CrmContact $contact)
    {
        $validated = $request->validate([
            'type' => 'required|in:call,email,meeting,note',
            'summary' => 'required|string',
            'interaction_date' => 'required|date',
        ]);

        CrmInteraction::create([
            'contact_id' => $contact->id,
            'user_id' => auth()->id(),
            'type' => $validated['type'],
            'summary' => $validated['summary'],
            'interaction_date' => $validated['interaction_date'],
        ]);

        return redirect()->route('crm.show', $contact)->with('success', 'Interaction logged');
    }

    public function destroy(CrmContact $contact)
    {
        $contact->delete();

        return redirect()->route('crm.index')->with('success', 'Contact deleted');
    }
}

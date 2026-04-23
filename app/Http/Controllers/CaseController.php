<?php

namespace App\Http\Controllers;

use App\Models\ClientCase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CaseController extends Controller
{
    /**
     * List all cases for the authenticated user
     */
    public function index(Request $request)
    {
        $query = Auth::user()->cases();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        $cases = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('client.cases', ['cases' => $cases]);
    }

    /**
     * Show the form for creating a new case
     */
    public function create()
    {
        return view('client.cases');
    }

    /**
     * Store a newly created case
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'case_type' => 'required|string|in:legal,consultation,support',
            'priority' => 'required|string|in:low,medium,high',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $caseNumber = 'CASE-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        $case = Auth::user()->cases()->create([
            'case_number' => $caseNumber,
            'title' => $request->title,
            'description' => $request->description,
            'case_type' => $request->case_type,
            'priority' => $request->priority,
            'status' => 'open',
            'filed_date' => now(),
        ]);

        return redirect()->route('cases.show', $case)
            ->with('success', 'Case created successfully. Case number: ' . $caseNumber);
    }

    /**
     * Display the specified case
     */
    public function show(ClientCase $case)
    {
        // Authorize user can view this case
        if (Auth::user()->id !== $case->user_id && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        return view('client.cases.show', ['matter' => $case]);
    }

    /**
     * Show the form for editing the case
     */
    public function edit(ClientCase $case)
    {
        if (Auth::user()->id !== $case->user_id) {
            abort(403, 'Unauthorized');
        }

        return view('client.cases', ['case' => $case]);
    }

    /**
     * Update the specified case
     */
    public function update(Request $request, ClientCase $case)
    {
        if (Auth::user()->id !== $case->user_id) {
            abort(403, 'Unauthorized');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'priority' => 'required|string|in:low,medium,high',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $case->update([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
        ]);

        return redirect()->route('cases.show', $case)
            ->with('success', 'Case updated successfully.');
    }

    /**
     * Withdraw a case
     */
    public function withdraw(Request $request, ClientCase $case)
    {
        if (Auth::user()->id !== $case->user_id) {
            abort(403, 'Unauthorized');
        }

        if ($case->status === 'withdrawn') {
            return redirect()->back()
                ->with('error', 'This case has already been withdrawn.');
        }

        $case->withdraw($request->input('reason'));

        return redirect()->route('cases.index')
            ->with('success', 'Case withdrawn successfully.');
    }

    /**
     * Delete a case (soft delete)
     */
    public function destroy(ClientCase $case)
    {
        if (Auth::user()->id !== $case->user_id && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized');
        }

        $case->delete();

        return redirect()->route('cases.index')
            ->with('success', 'Case deleted successfully.');
    }
}

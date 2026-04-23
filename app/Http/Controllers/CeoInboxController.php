<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;

class CeoInboxController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || !auth()->user()->isCeo()) {
                abort(403, 'Only CEO can access this');
            }
            return $next($request);
        });
    }

    /**
     * Show inbox for a specific worker
     */
    public function show(Request $request, User $worker)
    {
        // Ensure the worker is actually a worker
        if (!$worker->isWorker()) {
            abort(404, 'Worker not found');
        }

        // Get messages for this worker, paginated
        $messages = Message::where('recipient_id', $worker->id)
            ->orWhere('sender_id', $worker->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('ceo.inbox', [
            'worker' => $worker,
            'messages' => $messages,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ClientCase;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get active matters count (not completed)
        $activeMatters = ClientCase::where('user_id', $user->id)
            ->whereNotIn('status', ['completed', 'closed', 'withdrawn'])
            ->count();
        
        // Get paginated matters for the client, ordered by latest
        $matters = ClientCase::where('user_id', $user->id)
            ->where('status', '!=', 'closed')
            ->orderBy('updated_at', 'desc')
            ->paginate(8);
        
        // Get unread messages count
        $unreadMessages = Message::where('recipient_id', $user->id)
            ->where('is_read', false)
            ->whereNull('deleted_by_recipient')
            ->count();
        
        // Get recent messages (latest 5)
        $recentMessages = Message::where('recipient_id', $user->id)
            ->whereNull('deleted_by_recipient')
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get upcoming deadlines within next 30 days and calculate days remaining
        $upcomingDeadlines = ClientCase::where('user_id', $user->id)
            ->whereBetween('updated_at', [Carbon::now(), Carbon::now()->addDays(30)])
            ->orderBy('updated_at', 'asc')
            ->limit(5)
            ->get()
            ->map(function($deadline) {
                $daysRemaining = Carbon::now()->diffInDays($deadline->updated_at);
                $deadline->daysRemaining = $daysRemaining;
                $deadline->daysText = $daysRemaining == 1 ? 'in 1 day' : 'in ' . $daysRemaining . ' days';
                $deadline->daysColor = $daysRemaining <= 7 ? '#1a56db' : '#6b7280';
                return $deadline;
            });

        // Count documents (if they have many-to-many or relationship)
        $documentsCount = $matters->count() > 0 
            ? $matters->sum(function($matter) { return $matter->documents()->count(); })
            : 0;
        
        // Calculate greeting message
        $hour = Carbon::now()->hour;
        $greeting = $hour < 12 ? 'Good morning' : 'Good afternoon';
        
        return view('client.dashboard', compact(
            'activeMatters',
            'matters',
            'unreadMessages',
            'recentMessages',
            'upcomingDeadlines',
            'documentsCount',
            'user',
            'greeting'
        ));
    }
}

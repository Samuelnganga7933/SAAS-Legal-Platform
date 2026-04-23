<?php

namespace App\Http\Controllers;

use App\Models\ClientCase;
use App\Models\Task;
use App\Models\TimeLog;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkerDashboardController extends Controller
{
    /**
     * Show worker dashboard
     */
    public function index(): View
    {
        $user = auth()->user();

        // Get assigned cases for this worker
        $cases = $user->assignedCases()
                     ->where('status', '!=', 'withdrawn')
                     ->with('user', 'documents')
                     ->get();

        // Get pending tasks
        $pendingTasks = $user->assignedTasks()
                            ->where('status', '!=', 'done')
                            ->where('status', '!=', 'cancelled')
                            ->get();

        // Get overdue tasks
        $overdueTasks = $pendingTasks->filter(fn($task) => $task->isOverdue());

        // Get unread messages
        $recentMessages = Message::where('recipient_id', $user->id)
                                 ->where('read_at', null)
                                 ->latest()
                                 ->limit(5)
                                 ->get();

        // Get current time log session
        $currentTimeLog = null; // TODO: Implement active timer session

        // Get hours logged this week
        $weekHours = TimeLog::getTotalHoursForWeek($user->id);

        // Get case stats
        $caseStats = [
            'active' => $cases->where('status', 'in-progress')->count(),
            'open' => $cases->where('status', 'open')->count(),
            'closed' => $cases->where('status', 'closed')->count(),
        ];

        // Get task stats
        $taskStats = [
            'total' => $pendingTasks->count(),
            'overdue' => $overdueTasks->count(),
            'in_progress' => $pendingTasks->where('status', 'in_progress')->count(),
        ];

        return view('worker.dashboard', [
            'cases' => $cases,
            'pendingTasks' => $pendingTasks,
            'overdueTasks' => $overdueTasks,
            'recentMessages' => $recentMessages,
            'currentTimeLog' => $currentTimeLog,
            'weekHours' => $weekHours,
            'caseStats' => $caseStats,
            'taskStats' => $taskStats,
        ]);
    }

    /**
     * Get dashboard stats as JSON (for AJAX updates)
     */
    public function getStats(Request $request)
    {
        $user = auth()->user();

        $stats = [
            'unread_messages' => Message::where('recipient_id', $user->id)->where('read_at', null)->count(),
            'pending_tasks' => $user->assignedTasks()->where('status', '!=', 'done')->where('status', '!=', 'cancelled')->count(),
            'active_cases' => $user->assignedCases()->where('status', 'in-progress')->count(),
            'week_hours' => TimeLog::getTotalHoursForWeek($user->id),
        ];

        return response()->json($stats);
    }
}

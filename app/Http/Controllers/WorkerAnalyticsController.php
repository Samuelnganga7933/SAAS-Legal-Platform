<?php

namespace App\Http\Controllers;

use App\Models\ClientCase;
use App\Models\Task;
use App\Models\TimeLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WorkerAnalyticsController extends Controller
{
    /**
     * Show analytics dashboard
     */
    public function dashboard(Request $request): View
    {
        $user = auth()->user();
        $period = $request->query('period', '30');

        // Calculate stats based on period
        $fromDate = now()->subDays($period);

        // Case statistics
        $totalCases = $user->assignedCases()->count();
        $activeCases = $user->assignedCases()->where('status', '!=', 'closed')->count();
        $closedCases = $user->assignedCases()->where('status', 'closed')->count();

        // Task statistics
        $totalTasks = Task::whereIn('assigned_to', [$user->id])->count();
        $completedTasks = Task::whereIn('assigned_to', [$user->id])->where('status', 'done')->count();
        $inProgressTasks = Task::whereIn('assigned_to', [$user->id])->where('status', 'in_progress')->count();
        $overdueTasks = Task::whereIn('assigned_to', [$user->id])
                            ->where('due_date', '<', now())
                            ->where('status', '!=', 'done')
                            ->count();

        // Calculate completion rate
        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        $inProgressRate = $totalTasks > 0 ? round(($inProgressTasks / $totalTasks) * 100) : 0;
        $overdueRate = $totalTasks > 0 ? round(($overdueTasks / $totalTasks) * 100) : 0;

        // Hours statistics
        $timeLogs = TimeLog::where('user_id', $user->id)
                           ->where('date', '>=', $fromDate)
                           ->get();

        $totalHours = $timeLogs->sum('hours_logged');
        $billableHours = $timeLogs->where('billable', true)->sum('hours_logged');

        // Revenue calculation
        $hourlyRate = $user->hourly_rate ?? 150;
        $revenue = $billableHours * $hourlyRate;

        // Top cases by revenue
        $topCases = $this->getTopCasesByRevenue($user, $period);

        return view('worker.analytics.dashboard', [
            'stats' => [
                'total_cases' => $totalCases,
                'active_cases' => $activeCases,
                'closed_cases' => $closedCases,
                'total_tasks' => $totalTasks,
                'completed_tasks' => $completionRate,
                'in_progress_tasks' => $inProgressRate,
                'overdue_tasks' => $overdueRate,
                'total_hours' => round($totalHours, 1),
                'billable_hours' => round($billableHours, 1),
                'revenue' => round($revenue, 2),
            ],
            'period' => $period,
        ]);
    }

    /**
     * Get top cases by revenue
     */
    private function getTopCasesByRevenue($user, $period): array
    {
        $fromDate = now()->subDays($period);

        $cases = $user->assignedCases()
                     ->with('timeLogs')
                     ->get()
                     ->map(function ($case) use ($fromDate) {
                         $hours = $case->timeLogs()
                                      ->where('date', '>=', $fromDate)
                                      ->sum('hours_logged');

                         $billableHours = $case->timeLogs()
                                             ->where('date', '>=', $fromDate)
                                             ->where('billable', true)
                                             ->sum('hours_logged');

                         $revenue = $billableHours * ($case->hourly_rate ?? auth()->user()->hourly_rate ?? 150);

                         return [
                             'name' => $case->title,
                             'case_number' => $case->case_number,
                             'status' => $case->status,
                             'hours' => round($hours, 1),
                             'revenue' => round($revenue, 2),
                             'billable' => $hours > 0 ? round(($billableHours / $hours) * 100) : 0,
                         ];
                     })
                     ->sortByDesc('revenue')
                     ->take(5)
                     ->values();

        return $cases->toArray();
    }

    /**
     * Export analytics to PDF
     */
    public function exportPdf(Request $request)
    {
        // TODO: Integrate with PDF generation library (DOMPDF, Snappy, etc.)
        return redirect()->back()->with('error', 'PDF export not yet implemented');
    }

    /**
     * Export analytics to Excel
     */
    public function exportExcel(Request $request)
    {
        // TODO: Integrate with Excel export library (Laravel Excel, etc.)
        return redirect()->back()->with('error', 'Excel export not yet implemented');
    }
}

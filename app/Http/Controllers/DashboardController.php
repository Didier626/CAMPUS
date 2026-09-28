<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $baseQuery = ServiceRequest::query();
        $pendingCodes = ['registered', 'assigned', 'in_progress'];

        $metrics = [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->whereHas('status', fn ($query) => $query->whereIn('code', $pendingCodes))->count(),
            'resolved' => (clone $baseQuery)->whereHas('status', fn ($query) => $query->where('code', 'resolved'))->count(),
            'closed' => (clone $baseQuery)->whereHas('status', fn ($query) => $query->where('code', 'closed'))->count(),
            'averageHours' => (float) ((clone $baseQuery)->whereNotNull('resolved_at')
                ->selectRaw('AVG(EXTRACT(EPOCH FROM (resolved_at - submitted_at)) / 3600) AS average_hours')
                ->value('average_hours') ?? 0),
        ];

        $byPriority = DB::table('service_requests')
            ->join('priorities', 'priorities.id', '=', 'service_requests.priority_id')
            ->select('priorities.name', 'priorities.code', DB::raw('COUNT(*) AS total'))
            ->groupBy('priorities.id', 'priorities.name', 'priorities.code', 'priorities.level')
            ->orderBy('priorities.level')
            ->get();

        $recentRequests = ServiceRequest::with(['requester', 'priority', 'status'])
            ->latest()
            ->limit(6)
            ->get();

        return view('dashboard', compact('metrics', 'byPriority', 'recentRequests'));
    }
}

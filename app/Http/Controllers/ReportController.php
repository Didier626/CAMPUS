<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $query = ServiceRequest::query()
            ->when($request->filled('from'), fn ($builder) => $builder->whereDate('created_at', '>=', $request->date('from')))
            ->when($request->filled('to'), fn ($builder) => $builder->whereDate('created_at', '<=', $request->date('to')));

        $ids = (clone $query)->select('id');

        $byType = DB::table('service_requests')->join('request_types', 'request_types.id', '=', 'service_requests.request_type_id')
            ->whereIn('service_requests.id', $ids)->select('request_types.name', DB::raw('COUNT(*) AS total'))
            ->groupBy('request_types.id', 'request_types.name')->orderBy('request_types.name')->get();
        $byStatus = DB::table('service_requests')->join('request_statuses', 'request_statuses.id', '=', 'service_requests.status_id')
            ->whereIn('service_requests.id', $ids)->select('request_statuses.name', DB::raw('COUNT(*) AS total'))
            ->groupBy('request_statuses.id', 'request_statuses.name')->orderBy('request_statuses.id')->get();
        $byResponsible = DB::table('service_requests')->leftJoin('users', 'users.id', '=', 'service_requests.assigned_to_user_id')
            ->whereIn('service_requests.id', $ids)->selectRaw("COALESCE(users.name, 'Sin asignar') AS name, COUNT(*) AS total")
            ->groupBy('users.id', 'users.name')->orderByDesc('total')->get();

        return view('reports.index', [
            'total' => (clone $query)->count(),
            'closed' => (clone $query)->whereHas('status', fn ($status) => $status->where('code', 'closed'))->count(),
            'byType' => $byType, 'byStatus' => $byStatus, 'byResponsible' => $byResponsible,
        ]);
    }
}

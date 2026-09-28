<?php

namespace App\Http\Controllers;

use App\Models\Priority;
use App\Models\RequestComment;
use App\Models\RequestStatus;
use App\Models\RequestType;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ServiceRequestController extends Controller
{
    public function index(Request $request): View
    {
        $requests = ServiceRequest::with(['requester', 'assignedTo', 'priority', 'status', 'requestType'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->trim();
                $query->where(fn ($nested) => $nested
                    ->where('code', 'ilike', "%{$search}%")
                    ->orWhere('title', 'ilike', "%{$search}%"));
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status_id', $request->integer('status')))
            ->when($request->filled('priority'), fn ($query) => $query->where('priority_id', $request->integer('priority')))
            ->when($request->filled('type'), fn ($query) => $query->where('request_type_id', $request->integer('type')))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('requests.index', [
            'requests' => $requests,
            'statuses' => RequestStatus::where('is_active', true)->orderBy('id')->get(),
            'priorities' => Priority::where('is_active', true)->orderBy('level')->get(),
            'types' => RequestType::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function show(ServiceRequest $serviceRequest): View
    {
        $serviceRequest->load([
            'requester', 'assignedTo', 'priority', 'status', 'requestType', 'resources',
            'attachments.uploadedBy', 'comments.author', 'history.actor',
        ]);

        return view('requests.show', [
            'serviceRequest' => $serviceRequest,
            'staffUsers' => User::with('role')->where('is_active', true)->get()->filter->isStaff(),
            'priorities' => Priority::where('is_active', true)->orderBy('level')->get(),
            'statuses' => RequestStatus::where('is_active', true)->orderBy('id')->get(),
        ]);
    }

    public function updateAssignment(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $data = $request->validate([
            'assigned_to_user_id' => ['nullable', Rule::exists('users', 'id')],
            'priority_id' => ['required', Rule::exists('priorities', 'id')],
        ]);

        $assignee = isset($data['assigned_to_user_id']) ? User::with('role')->find($data['assigned_to_user_id']) : null;
        if ($assignee && ! $assignee->isStaff()) {
            return back()->withErrors(['assigned_to_user_id' => 'El responsable debe ser administrativo o administrador.']);
        }

        DB::transaction(function () use ($serviceRequest, $data, $assignee, $request) {
            $oldValues = ['assigned_to_user_id' => $serviceRequest->assigned_to_user_id, 'priority_id' => $serviceRequest->priority_id];
            $serviceRequest->priority_id = $data['priority_id'];
            $serviceRequest->assigned_to_user_id = $assignee?->id;

            if ($assignee && $serviceRequest->status->code === 'registered') {
                $serviceRequest->status_id = RequestStatus::where('code', 'assigned')->value('id');
                $serviceRequest->assigned_at = now();
            }

            $serviceRequest->save();
            $this->recordHistory($serviceRequest, $request->user()->id, 'assignment_updated', $oldValues, [
                'assigned_to_user_id' => $serviceRequest->assigned_to_user_id,
                'priority_id' => $serviceRequest->priority_id,
            ]);
        });

        return back()->with('success', 'Responsable y prioridad actualizados.');
    }

    public function updateStatus(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $data = $request->validate([
            'status_id' => ['required', Rule::exists('request_statuses', 'id')],
            'note' => ['nullable', 'string', 'max:2000'],
        ]);

        $nextStatus = RequestStatus::findOrFail($data['status_id']);
        $currentCode = $serviceRequest->status->code;
        $allowedTransitions = [
            'registered' => ['assigned', 'cancelled'],
            'assigned' => ['registered', 'in_progress', 'cancelled'],
            'in_progress' => ['registered', 'resolved', 'cancelled'],
            'resolved' => ['closed'],
        ];

        if (! in_array($nextStatus->code, $allowedTransitions[$currentCode] ?? [], true)) {
            return back()->withErrors(['status_id' => 'La transición de estado no está permitida.']);
        }

        if ($nextStatus->code === 'resolved' && blank($data['note'])) {
            return back()->withErrors(['note' => 'Debes registrar la solución antes de marcar la solicitud como resuelta.']);
        }

        DB::transaction(function () use ($serviceRequest, $nextStatus, $data, $request) {
            $previous = $serviceRequest->status->only(['id', 'code', 'name']);
            $serviceRequest->status_id = $nextStatus->id;

            match ($nextStatus->code) {
                'in_progress' => $serviceRequest->started_at = now(),
                'resolved' => $serviceRequest->resolved_at = now(),
                'closed' => $serviceRequest->closed_at = now(),
                'cancelled' => $serviceRequest->cancelled_at = now(),
                default => null,
            };

            $serviceRequest->save();
            $this->recordHistory($serviceRequest, $request->user()->id, 'status_updated', $previous, $nextStatus->only(['id', 'code', 'name']), $data['note'] ?? null);

            if ($nextStatus->code === 'resolved') {
                RequestComment::create([
                    'service_request_id' => $serviceRequest->id,
                    'author_user_id' => $request->user()->id,
                    'content' => $data['note'],
                ]);
            }
        });

        return back()->with('success', 'Estado actualizado correctamente.');
    }

    public function storeComment(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $data = $request->validate(['content' => ['required', 'string', 'max:2000']]);

        DB::transaction(function () use ($data, $serviceRequest, $request) {
            RequestComment::create([
                'service_request_id' => $serviceRequest->id,
                'author_user_id' => $request->user()->id,
                'content' => $data['content'],
            ]);
            $this->recordHistory($serviceRequest, $request->user()->id, 'comment_added', null, null, $data['content']);
        });

        return back()->with('success', 'Comentario registrado.');
    }

    private function recordHistory(ServiceRequest $serviceRequest, ?int $actorId, string $action, ?array $oldValues = null, ?array $newValues = null, ?string $note = null): void
    {
        $serviceRequest->history()->create([
            'actor_user_id' => $actorId,
            'action' => $action,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'note' => $note,
        ]);
    }
}

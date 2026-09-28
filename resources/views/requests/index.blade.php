@extends('layouts.app')

@section('title', 'Solicitudes | Campus Connect')

@section('content')
    <h1>Solicitudes</h1>
    <p class="subtitle">Consulta, filtra y prioriza los requerimientos registrados.</p>
    <form class="filters" method="GET">
        <div class="field"><label for="search">Código o título</label><input id="search" name="search" value="{{ request('search') }}" placeholder="SOL-2026..."/></div>
        <div class="field"><label for="status">Estado</label><select id="status" name="status"><option value="">Todos</option>@foreach($statuses as $status)<option value="{{ $status->id }}" @selected(request('status') == $status->id)>{{ $status->name }}</option>@endforeach</select></div>
        <div class="field"><label for="priority">Prioridad</label><select id="priority" name="priority"><option value="">Todas</option>@foreach($priorities as $priority)<option value="{{ $priority->id }}" @selected(request('priority') == $priority->id)>{{ $priority->name }}</option>@endforeach</select></div>
        <div class="field"><label for="type">Tipo</label><select id="type" name="type"><option value="">Todos</option>@foreach($types as $type)<option value="{{ $type->id }}" @selected(request('type') == $type->id)>{{ $type->name }}</option>@endforeach</select></div>
        <div style="align-self:end"><button class="btn btn-primary">Filtrar</button> <a class="btn btn-muted" href="{{ route('requests.index') }}">Limpiar</a></div>
    </form>
    <table>
        <thead><tr><th>Código</th><th>Solicitud</th><th>Tipo</th><th>Solicitante</th><th>Responsable</th><th>Prioridad</th><th>Estado</th></tr></thead>
        <tbody>@forelse($requests as $serviceRequest)<tr>
            <td><a href="{{ route('requests.show', $serviceRequest) }}">{{ $serviceRequest->code }}</a></td><td>{{ $serviceRequest->title }}<br><small>{{ $serviceRequest->location }}</small></td><td>{{ $serviceRequest->requestType->name }}</td><td>{{ $serviceRequest->requester->name }}</td><td>{{ $serviceRequest->assignedTo?->name ?? 'Sin asignar' }}</td><td><span class="badge">{{ $serviceRequest->priority->name }}</span></td><td><span class="badge">{{ $serviceRequest->status->name }}</span></td>
        </tr>@empty<tr><td colspan="7">No hay solicitudes que coincidan con los filtros.</td></tr>@endforelse</tbody>
    </table>
    <div class="section">{{ $requests->links() }}</div>
@endsection

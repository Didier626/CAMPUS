@extends('layouts.app')

@section('title', 'Dashboard | Campus Connect')

@section('content')
    <h1>Dashboard administrativo</h1>
    <p class="subtitle">Resumen actual de las solicitudes institucionales.</p>

    <div class="grid">
        <div class="card"><div class="metric-label">Total de solicitudes</div><div class="metric">{{ $metrics['total'] }}</div></div>
        <div class="card"><div class="metric-label">Pendientes de atención</div><div class="metric">{{ $metrics['pending'] }}</div></div>
        <div class="card"><div class="metric-label">Resueltas</div><div class="metric">{{ $metrics['resolved'] }}</div></div>
        <div class="card"><div class="metric-label">Tiempo medio de resolución</div><div class="metric">{{ number_format($metrics['averageHours'], 1) }} h</div></div>
    </div>

    <div class="two-columns">
        <section class="card">
            <h2>Solicitudes por prioridad</h2>
            @forelse($byPriority as $priority)
                <div class="row" style="padding:10px 0;border-bottom:1px solid #e6eef5"><span>{{ $priority->name }}</span><strong>{{ $priority->total }}</strong></div>
            @empty
                <p class="subtitle">No hay solicitudes registradas.</p>
            @endforelse
        </section>
        <section class="card">
            <h2>Acciones rápidas</h2>
            <p class="subtitle">Gestiona los elementos principales del sistema.</p>
            <p><a class="btn btn-primary" href="{{ route('requests.index') }}">Ver solicitudes</a></p>
            <p><a class="btn btn-muted" href="{{ route('resources.index') }}">Gestionar recursos</a></p>
        </section>
    </div>

    <section class="section">
        <div class="row"><h2>Solicitudes recientes</h2><a href="{{ route('requests.index') }}">Ver todas</a></div>
        <table>
            <thead><tr><th>Código</th><th>Solicitud</th><th>Estudiante</th><th>Prioridad</th><th>Estado</th></tr></thead>
            <tbody>
            @forelse($recentRequests as $request)
                <tr><td><a href="{{ route('requests.show', $request) }}">{{ $request->code }}</a></td><td>{{ $request->title }}</td><td>{{ $request->requester->name }}</td><td><span class="badge">{{ $request->priority->name }}</span></td><td><span class="badge">{{ $request->status->name }}</span></td></tr>
            @empty
                <tr><td colspan="5">Aún no hay solicitudes. Se mostrarán aquí cuando el móvil consuma la API.</td></tr>
            @endforelse
            </tbody>
        </table>
    </section>
@endsection

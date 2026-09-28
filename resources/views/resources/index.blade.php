@extends('layouts.app')

@section('title', 'Recursos | Campus Connect')

@section('content')
    <h1>Recursos institucionales</h1><p class="subtitle">Administra aulas, equipamiento y otros recursos sin eliminar su historial.</p>
    <div class="two-columns"><section class="card"><h2>Registrar recurso</h2><form method="POST" action="{{ route('resources.store') }}">@csrf @include('resources.form', ['resource' => null])<button class="btn btn-primary" style="margin-top:16px">Crear recurso</button></form></section><section class="card"><h2>Estados disponibles</h2><p><span class="badge success">Disponible</span> Puede utilizarse normalmente.</p><p><span class="badge warning">Mantenimiento</span> Requiere atención técnica.</p><p><span class="badge danger">Fuera de servicio</span> No está operativo.</p></section></div>
    <section class="section"><h2>Listado de recursos</h2><table><thead><tr><th>Código</th><th>Recurso</th><th>Ubicación</th><th>Estado</th><th>Activo</th><th>Acciones</th></tr></thead><tbody>@forelse($resources as $resource)<tr><td>{{ $resource->code }}</td><td><strong>{{ $resource->name }}</strong><br><small>{{ $resource->resource_type }}</small></td><td>{{ $resource->location }}</td><td>{{ $resource->status }}</td><td>{{ $resource->is_active ? 'Sí' : 'No' }}</td><td><form method="POST" action="{{ route('resources.deactivate', $resource) }}" onsubmit="return confirm('¿Desactivar este recurso?')">@csrf @method('DELETE')<button class="btn btn-danger" type="submit" @disabled(! $resource->is_active)>Desactivar</button></form></td></tr>@empty<tr><td colspan="6">No hay recursos registrados.</td></tr>@endforelse</tbody></table><div class="section">{{ $resources->links() }}</div></section>
@endsection

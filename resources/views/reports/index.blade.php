@extends('layouts.app')
@section('title', 'Reportes | Campus Connect')
@section('content')
    <h1>Reportes</h1><p class="subtitle">Información consolidada para la gestión administrativa.</p>
    <form class="filters" method="GET"><div class="field"><label for="from">Desde</label><input id="from" type="date" name="from" value="{{ request('from') }}"></div><div class="field"><label for="to">Hasta</label><input id="to" type="date" name="to" value="{{ request('to') }}"></div><div style="align-self:end"><button class="btn btn-primary">Aplicar período</button> <a class="btn btn-muted" href="{{ route('reports.index') }}">Limpiar</a></div></form>
    <div class="grid"><div class="card"><div class="metric-label">Solicitudes del período</div><div class="metric">{{ $total }}</div></div><div class="card"><div class="metric-label">Solicitudes cerradas</div><div class="metric">{{ $closed }}</div></div></div>
    <div class="two-columns"><section class="card"><h2>Por tipo</h2>@forelse($byType as $item)<div class="row" style="padding:8px 0;border-bottom:1px solid #e6eef5"><span>{{ $item->name }}</span><strong>{{ $item->total }}</strong></div>@empty<p class="subtitle">Sin datos.</p>@endforelse</section><section class="card"><h2>Por estado</h2>@forelse($byStatus as $item)<div class="row" style="padding:8px 0;border-bottom:1px solid #e6eef5"><span>{{ $item->name }}</span><strong>{{ $item->total }}</strong></div>@empty<p class="subtitle">Sin datos.</p>@endforelse</section></div>
    <section class="section card"><h2>Carga por responsable</h2>@forelse($byResponsible as $item)<div class="row" style="padding:8px 0;border-bottom:1px solid #e6eef5"><span>{{ $item->name }}</span><strong>{{ $item->total }}</strong></div>@empty<p class="subtitle">Sin datos.</p>@endforelse</section>
@endsection

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Campus Connect')</title>
    <style>
        :root { --navy:#102a43; --blue:#2274a5; --light:#f5f7fa; --border:#d9e2ec; --text:#243b53; --muted:#627d98; --success:#1f7a5c; --danger:#b42318; }
        * { box-sizing:border-box; } body { margin:0; font-family:Arial, sans-serif; background:var(--light); color:var(--text); }
        nav { background:var(--navy); color:#fff; padding:0 32px; display:flex; align-items:center; gap:24px; min-height:62px; } nav strong { font-size:18px; margin-right:auto; } nav a { color:#d9e2ec; text-decoration:none; } nav a.active, nav a:hover { color:#fff; } nav form { margin:0; } button { cursor:pointer; }
        .container { max-width:1200px; margin:0 auto; padding:28px 20px; } h1 { margin:0 0 8px; font-size:28px; } h2 { font-size:19px; } .subtitle { color:var(--muted); margin:0 0 24px; }
        .grid { display:grid; grid-template-columns:repeat(4, 1fr); gap:16px; margin-bottom:24px; } .card { background:#fff; border:1px solid var(--border); border-radius:10px; padding:18px; } .metric-label { color:var(--muted); font-size:14px; } .metric { font-size:30px; font-weight:bold; margin-top:8px; }
        .row { display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; } .filters { background:#fff; padding:16px; border:1px solid var(--border); border-radius:10px; display:flex; gap:12px; flex-wrap:wrap; margin:18px 0; }
        input, select, textarea { width:100%; border:1px solid #bcccdc; border-radius:6px; padding:10px; font:inherit; background:#fff; } label { display:block; font-size:13px; font-weight:bold; margin-bottom:6px; } textarea { min-height:96px; resize:vertical; } .field { min-width:160px; flex:1; }
        .btn { border:0; border-radius:6px; padding:10px 14px; font:inherit; text-decoration:none; display:inline-block; } .btn-primary { background:var(--blue); color:#fff; } .btn-muted { background:#e6eef5; color:var(--text); } .btn-danger { background:var(--danger); color:#fff; }
        table { width:100%; border-collapse:collapse; background:#fff; border:1px solid var(--border); border-radius:10px; overflow:hidden; } th, td { padding:13px; border-bottom:1px solid #e6eef5; text-align:left; vertical-align:top; } th { color:var(--muted); font-size:12px; text-transform:uppercase; } tr:last-child td { border-bottom:0; }
        .badge { display:inline-block; padding:4px 8px; border-radius:20px; background:#e6eef5; color:var(--text); font-size:12px; } .success { background:#d9f7e9; color:#126b4b; } .warning { background:#fff1c7; color:#7a4c00; } .danger { background:#fee4e2; color:#b42318; }
        .alert { padding:12px 14px; border-radius:7px; margin:0 0 18px; } .alert-success { background:#d9f7e9; color:#126b4b; } .alert-error { background:#fee4e2; color:#b42318; } .two-columns { display:grid; grid-template-columns:1fr 1fr; gap:20px; } .details dt { color:var(--muted); font-size:12px; margin-top:12px; } .details dd { margin:3px 0 0; } .section { margin-top:24px; }
        @media (max-width:800px) { nav { padding:12px 18px; flex-wrap:wrap; } .grid { grid-template-columns:repeat(2, 1fr); } .two-columns { grid-template-columns:1fr; } .container { padding:20px 12px; } } @media (max-width:480px) { .grid { grid-template-columns:1fr; } }
    </style>
</head>
<body>
<nav>
    <strong>Campus Connect</strong>
    <a href="{{ route('dashboard') }}" @class(['active' => request()->routeIs('dashboard'))>Dashboard</a>
    <a href="{{ route('requests.index') }}" @class(['active' => request()->routeIs('requests.*'))>Solicitudes</a>
    <a href="{{ route('resources.index') }}" @class(['active' => request()->routeIs('resources.*'))>Recursos</a>
    <a href="{{ route('reports.index') }}" @class(['active' => request()->routeIs('reports.*'))>Reportes</a>
    <span>{{ auth()->user()->name }}</span>
    <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-muted" type="submit">Salir</button></form>
</nav>
<main class="container">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert alert-error"><strong>Revisa los datos:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    @yield('content')
</main>
</body>
</html>

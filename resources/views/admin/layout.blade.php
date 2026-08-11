<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin - Contact Bridge' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --ink:#111827; --muted:#667085; --line:#dde3ec; --blue:#305cf6; --green:#16a36f; --coral:#ff6f61; --navy:#111a33; }
        * { box-sizing:border-box; }
        body { margin:0; color:var(--ink); background:linear-gradient(90deg,rgba(17,26,51,.045) 1px,transparent 1px),linear-gradient(180deg,rgba(17,26,51,.045) 1px,transparent 1px),linear-gradient(180deg,#f8fafc 0%,#eef3f8 100%); background-size:44px 44px,44px 44px,auto; font-family:"DM Sans",Arial,sans-serif; }
        a { color:inherit; }
        .app-shell { width:min(1380px,calc(100% - 40px)); min-height:100vh; margin:0 auto; display:grid; grid-template-columns:238px minmax(0,1fr); gap:32px; }
        .sidebar { position:sticky; top:18px; align-self:start; height:calc(100vh - 36px); padding:24px 16px; border:1px solid #273a70; border-radius:12px; background:linear-gradient(160deg,#17264c 0%,#111a33 58%,#0d1530 100%); box-shadow:0 26px 70px rgba(17,26,51,.22); }
        .wrap { min-width:0; padding:24px 0 60px; }
        .topbar { position:sticky; top:18px; z-index:20; display:flex; justify-content:space-between; align-items:center; gap:20px; min-height:64px; margin-bottom:42px; padding:0 14px 0 16px; border:1px solid rgba(199,211,237,.9); border-radius:10px; background:rgba(244,248,255,.88); box-shadow:0 16px 38px rgba(17,26,51,.11); backdrop-filter:blur(14px); }
        .brand { display:flex; align-items:center; gap:11px; color:var(--ink); text-decoration:none; font:800 17px Manrope,sans-serif; }
        .sidebar .brand { color:#fff; }
        .mark { width:35px; height:35px; display:grid; place-items:center; border-radius:8px; background:var(--navy); color:#fff; box-shadow:0 12px 30px rgba(17,26,51,.18); }
        .sidebar .mark { background:var(--blue); box-shadow:0 12px 30px rgba(48,92,246,.34); }
        .mark:before { content:"CB"; font-size:11px; letter-spacing:.08em; }
        .topbar-right, .actions { display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
        .sidebar-nav { display:grid; gap:5px; margin-top:48px; }
        .sidebar-label { margin:0 10px 8px; color:#9daed6; font-size:10px; font-weight:800; letter-spacing:.13em; text-transform:uppercase; }
        .nav-link, .button { border:1px solid transparent; border-radius:8px; padding:10px 13px; color:#526076; font-size:13px; font-weight:700; text-decoration:none; background:transparent; cursor:pointer; }
        .nav-link:hover { color:var(--ink); background:rgba(255,255,255,.7); }
        .sidebar .nav-link { display:flex; align-items:center; gap:10px; padding:11px 12px; color:#d8e3ff; }
        .sidebar .nav-link:hover { color:#fff; background:rgba(255,255,255,.09); }
        .sidebar .nav-link.active { color:#fff; background:linear-gradient(90deg,rgba(48,92,246,.92),rgba(78,113,255,.65)); box-shadow:inset 0 0 0 1px rgba(169,187,255,.28),0 8px 18px rgba(0,0,0,.16); }
        .nav-icon { display:inline-grid; place-items:center; width:22px; height:22px; border-radius:6px; background:#f0f3f8; color:#526076; font-size:11px; font-weight:800; }
        .sidebar .nav-icon { background:rgba(255,255,255,.1); color:#cbd9ff; }
        .sidebar .nav-link.active .nav-icon { background:rgba(255,255,255,.18); color:#fff; }
        .sidebar-bottom { margin-top:28px; padding:17px; border:1px solid rgba(185,202,255,.18); border-radius:8px; background:rgba(255,255,255,.07); color:#b7c5e9; font-size:12px; line-height:1.55; }
        .button { background:var(--navy); color:#fff; box-shadow:0 10px 22px rgba(17,26,51,.14); }
        .button:hover { background:#1d2b51; }
        .button.secondary { background:#fff; border-color:var(--line); color:#526076; box-shadow:none; }
        .button.secondary:hover { border-color:#b8c3d4; color:var(--ink); }
        .page-head { display:flex; justify-content:space-between; align-items:end; gap:20px; margin-bottom:24px; }
        h1 { margin:0 0 7px; font:800 clamp(30px,4vw,44px)/1.05 Manrope,sans-serif; letter-spacing:-.035em; }
        h2 { font:800 19px Manrope,sans-serif; }
        .eyebrow { color:var(--coral); font-size:11px; font-weight:800; letter-spacing:.14em; text-transform:uppercase; }
        .muted { color:var(--muted); }
        .card { border:1px solid var(--line); border-radius:8px; background:rgba(255,255,255,.9); box-shadow:0 22px 60px rgba(17,26,51,.08); overflow:hidden; }
        .card-pad { padding:24px; }
        table { width:100%; border-collapse:collapse; }
        th,td { padding:16px 14px; border-bottom:1px solid #edf1f6; text-align:left; vertical-align:top; font-size:13px; }
        th { color:#718096; font-size:10px; text-transform:uppercase; letter-spacing:.1em; background:#fbfcfe; }
        tr:last-child td { border-bottom:0; }
        td a { color:var(--blue); text-decoration:none; }
        td a:hover { text-decoration:underline; }
        .badge { display:inline-block; padding:5px 9px; border-radius:999px; background:#eef2ff; color:#3151b7; font-size:11px; font-weight:700; text-transform:capitalize; }
        .badge.sent, .badge.resolved { background:#ecfdf3; color:#08724b; }
        .badge.failed { background:#fff1ee; color:#bd3528; }
        .badge.pending, .badge.in_progress { background:#fff7e8; color:#9a6500; }
        input, select { min-height:38px; padding:9px 11px; border:1px solid #d7deea; border-radius:8px; outline:0; background:#fff; color:var(--ink); font:13px "DM Sans",Arial,sans-serif; }
        input:focus, select:focus { border-color:var(--blue); box-shadow:0 0 0 3px rgba(48,92,246,.12); }
        .alert { margin-bottom:18px; padding:13px 15px; border:1px solid #bce8d2; border-radius:8px; background:#ecfdf3; color:#08724b; font-size:13px; }
        .alert.error { border-color:#ffc7c0; background:#fff1ee; color:#bd3528; }
        .empty { padding:46px 20px; text-align:center; }
        .pagination { display:flex; align-items:center; justify-content:space-between; gap:14px; padding:18px; border-top:1px solid #edf1f6; }
        .pagination nav { display:flex; justify-content:center; margin-left:auto; }
        .pagination svg { width:17px; }
        .pagination a, .pagination span { display:inline-flex; align-items:center; justify-content:center; min-width:32px; height:32px; margin:0 2px; border:1px solid var(--line); border-radius:7px; color:#526076; font-size:12px; text-decoration:none; }
        .pagination span[aria-current="page"] { background:var(--navy); color:#fff; border-color:var(--navy); }
        @media(max-width:900px) { .app-shell{width:min(100% - 28px,1380px);display:block}.sidebar{position:sticky;top:10px;z-index:30;height:auto;margin-top:10px;padding:12px;border-radius:10px}.sidebar-nav{display:flex;gap:6px;margin-top:14px;overflow:auto}.sidebar-label,.sidebar-bottom{display:none}.sidebar .nav-link{width:auto;white-space:nowrap}.topbar{top:10px;margin-bottom:30px}.topbar .brand{display:none} }
        @media(max-width:760px) { .topbar,.page-head{align-items:flex-start; flex-direction:column} .topbar-right{width:100%} .table-scroll{overflow:auto} table{min-width:780px} .card-pad{padding:18px} .pagination{align-items:flex-start;flex-direction:column}.pagination nav{margin-left:0} }
    </style>
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <a class="brand" href="{{ route('admin.dashboard') }}"><span class="mark"></span>Contact Bridge</a>
        <nav class="sidebar-nav">
            <div class="sidebar-label">Workspace</div>
            <a class="nav-link @if(request()->routeIs('admin.dashboard')) active @endif" href="{{ route('admin.dashboard') }}"><span class="nav-icon">D</span>Dashboard</a>
            <a class="nav-link @if(request()->routeIs('admin.submissions.*')) active @endif" href="{{ route('admin.submissions.index') }}"><span class="nav-icon">S</span>Submissions</a>
            <a class="nav-link @if(request()->routeIs('admin.sources.*')) active @endif" href="{{ route('admin.sources.index') }}"><span class="nav-icon">O</span>Sources</a>
            <div class="sidebar-label" style="margin-top:22px">Resources</div>
            <a class="nav-link" href="{{ route('developers') }}"><span class="nav-icon">A</span>API docs</a>
        </nav>
        <div class="sidebar-bottom"><strong style="color:#fff">Protected routing</strong><br>Only active, database-managed origins can deliver submissions.</div>
    </aside>
    <div class="wrap">
    <header class="topbar">
        <div class="brand"><span class="mark"></span>Operations workspace</div>
        <div class="topbar-right">
            <a class="nav-link" href="{{ route('developers') }}">API docs</a>
            <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="button secondary" type="submit">Sign out</button></form>
        </div>
    </header>
    @if(session('success'))<div class="alert">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert error">{{ session('error') }}</div>@endif
    @if($errors->any())<div class="alert error">{{ $errors->first() }}</div>@endif
    @yield('content')
    </div>
</div>
</body>
</html>

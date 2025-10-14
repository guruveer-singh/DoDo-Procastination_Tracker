<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ffd600">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="layout-container">
        <!-- Vertical Sidebar -->
        <aside class="sidebar">
            <a href="{{ route('tasks.index') }}" class="logo">D</a>
            <nav>
    <a href="{{ route('tasks.index') }}" class="nav-item {{ request()->routeIs('tasks.index') ? 'active' : '' }}" title="Tasks">
        <i class="material-icons-round">check_circle</i>
    </a>
    <a href="{{ route('reminders.index') }}" class="nav-item {{ request()->routeIs('reminders.*') ? 'active' : '' }}" title="Reminders">
        <i class="material-icons-round">notifications</i>
    </a>
    <div class="divider"></div>
    <a href="{{ route('tasks.archived') }}" class="nav-item {{ request()->routeIs('tasks.archived') ? 'active' : '' }}" title="Archived Tasks">
        <i class="material-icons-round">archive</i>
    </a>
</nav>
        </aside>
        
        <!-- Main Content -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    @stack('scripts')
    <script src="{{ asset('js/custom.js') }}"></script>
</body>
</html>
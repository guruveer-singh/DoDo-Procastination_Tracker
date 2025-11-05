<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
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
    <style>
        :root {
            --bg-primary: #ffffff;
            --text-primary: #1f2937;
            --bg-secondary: #f3f4f6;
            --text-secondary: #4b5563;
            --sidebar-width: 5rem;
        }
        
        .dark {
            --bg-primary: #111827;
            --text-primary: #f9fafb;
            --bg-secondary: #1f2937;
            --text-secondary: #d1d5db;
        }
        
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        
        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            transition: background-color 0.3s, color 0.3s;
            display: flex;
            flex-direction: column;
        }
        
        .app-layout {
            display: flex;
            min-height: 100vh;
            width: 100%;
            position: relative;
            z-index: 1;
            padding-left: calc(var(--sidebar-width) + 12px);
        }
        
        .sidebar {
            width: var(--sidebar-width);
            background-color: #fcd34d;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.5rem 0;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 10;
            border-right: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .sidebar .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #7c2d12;
            margin-bottom: 2rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
        }
        
        .sidebar nav {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            gap: 10px;
        }
        
        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            color: #7c2d12;
            text-decoration: none;
            transition: all 0.2s ease;
            position: relative;
            margin: 5px 0;
            border-radius: 8px;
        }
        
        .nav-item i {
            font-size: 1.25rem;
            margin-bottom: 4px;
        }
        
        .nav-item span {
            font-size: 0.6rem;
            font-weight: 500;
            color: #7c2d12;
            position: absolute;
            bottom: -16px;
            white-space: nowrap;
            text-align: center;
            width: 100%;
        }
        
        .nav-item:hover, .nav-item.active {
            background-color: rgba(220, 150, 50, 0.2);
            color: #7c2d12;
        }
        
        .nav-item:hover span, .nav-item.active span {
            color: #7c2d12;
            font-weight: 600;
        }
        
        .nav-item.active {
            background-color: rgba(220, 150, 50, 0.3);
        }
        
        .divider {
            width: 24px;
            height: 1px;
            background-color: rgba(124, 45, 18, 0.3);
            margin: 10px 0;
        }

        .toast-container {
            position: fixed;
            top: 16px;
            right: 16px;
            z-index: 50;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .toast {
            min-width: 260px;
            max-width: 360px;
            background: #ffffff;
            color: #111827;
            border: 1px solid #f3f4f6;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
            padding: 10px 12px;
            display: flex;
            align-items: start;
            gap: 10px;
        }
        .dark .toast { background: #1f2937; color: #f9fafb; border-color: #374151; }
        .toast-title { font-weight: 600; font-size: 14px; }
        .toast-time { font-size: 12px; opacity: 0.8; }
        .toast-close { margin-left: auto; cursor: pointer; opacity: 0.7; }
        .toast-close:hover { opacity: 1; }
    </style>
</head>
<body>
    <div class="app-layout">
        <!-- Vertical Sidebar -->
        <aside class="sidebar">
            <a href="{{ route('tasks.index') }}" class="logo">D</a>
            <nav>
                <a href="{{ route('tasks.index') }}" class="nav-item {{ request()->routeIs('tasks.index') ? 'active' : '' }}" title="Tasks" aria-label="Tasks">
                    <i class="material-icons-round">check_circle</i>
                    <span>Tasks</span>
                </a>
                
                <a href="{{ route('reminders.index') }}" class="nav-item {{ request()->routeIs('reminders.*') ? 'active' : '' }}" title="Reminders" aria-label="Reminders">
                    <i class="material-icons-round">notifications</i>
                    <span>Reminders</span>
                </a>
                
                <div class="divider"></div>
                
                <a href="{{ route('notes.index') }}" class="nav-item {{ request()->routeIs('notes.*') ? 'active' : '' }}" title="Notes" aria-label="Notes">
                    <i class="material-icons-round">sticky_note_2</i>
                    <span>Notes</span>
                </a>

                <div class="divider"></div>

                <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}" title="Reports" aria-label="Reports">
                    <i class="material-icons-round">leaderboard</i>
                    <span>Reports</span>
                </a>

                <div class="divider"></div>
                
                <a href="{{ route('tasks.archived') }}" class="nav-item {{ request()->routeIs('tasks.archived') ? 'active' : '' }}" title="Archive" aria-label="Archive">
                    <i class="material-icons-round">archive</i>
                    <span>Archive</span>
                </a>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="flex-1 p-6 relative z-20">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                        Hello, {{ Auth::user()->name }} 👋
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">Welcome back to your dashboard</p>
                </div>
                <div class="flex items-center space-x-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center space-x-1 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                            <i class="material-icons-round text-xl">logout</i>
                            <span class="text-sm font-medium">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
            @yield('content')
        </main>
    </div>

    <div id="toast-root" class="toast-container"></div>

    <!-- Scripts -->
    @stack('scripts')
    <script>
        (function(){
            const root = document.getElementById('toast-root');
            let lastShownIds = new Set();
            let pollingBadge;
            function showToast(title, description, time) {
                const wrap = document.createElement('div');
                wrap.className = 'toast';
                wrap.innerHTML = `
                    <div>
                        <div class="toast-title">${title}</div>
                        <div class="text-sm">${description || ''}</div>
                        <div class="toast-time">${time || ''}</div>
                    </div>
                    <button class="toast-close" aria-label="Close">✕</button>
                `;
                wrap.querySelector('.toast-close').addEventListener('click', ()=> wrap.remove());
                root.appendChild(wrap);
                setTimeout(()=> wrap.remove(), 8000);
            }
            async function ensurePermission(){
                try {
                    if (!('Notification' in window)) return false;
                    if (Notification.permission === 'granted') return true;
                    if (Notification.permission !== 'denied') {
                        const p = await Notification.requestPermission();
                        return p === 'granted';
                    }
                } catch(e) {}
                return false;
            }
            function notifyBrowser(title, body){
                try { new Notification(title, { body }); } catch(e) {}
            }
            function ensurePollingBadge(){
                if (pollingBadge) return;
                pollingBadge = document.createElement('div');
                pollingBadge.style.cssText = 'position:fixed;bottom:10px;right:12px;font-size:11px;color:#6b7280;opacity:.6;z-index:40;';
                pollingBadge.setAttribute('aria-hidden', 'true');
                document.body.appendChild(pollingBadge);
            }
            async function pollDue(){
                try{
                    const res = await fetch('/reminders/due', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    if(!res.ok) return;
                    const json = await res.json();
                    const allowed = await ensurePermission();
                    const items = json.data || [];
                    if (items.length === 0) {
                        // no items this tick
                    }
                    items.forEach(r => {
                        if (lastShownIds.has(r.id)) return;
                        lastShownIds.add(r.id);
                        showToast(r.title, r.description, new Date(r.reminder_time).toLocaleTimeString());
                        if (allowed) notifyBrowser('Reminder', `${r.title}`);
                    });
                    if (lastShownIds.size > 1000) lastShownIds = new Set(Array.from(lastShownIds).slice(-200));
                    ensurePollingBadge();
                    const t = new Date();
                    pollingBadge.textContent = `Reminders checked ${t.toLocaleTimeString()}`;
                    console.debug('[Reminders] Poll ok', { count: items.length, now: json.now });
                }catch(e){}
            }
            // Kick things off
            ensurePermission();
            pollDue();
            setInterval(pollDue, 15000);
        })();
    </script>
    <script src="{{ asset('js/custom.js') }}"></script>
</body>
</html>
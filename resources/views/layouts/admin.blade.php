<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - Flixora')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/flixora.css') }}">

    <style>
        /* Inline critical layout reset for admin */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
            background: var(--bg-main);
        }
    </style>
</head>
<body>

    <div class="admin-wrapper">

        {{-- ===== SIDEBAR ===== --}}
        <aside class="admin-sidebar">

            {{-- Brand --}}
            <a href="{{ route('home') }}" class="admin-brand">
                FLIXORA&nbsp;<span class="admin-badge-label">ADMIN</span>
            </a>

            {{-- Navigation --}}
            <nav class="admin-nav">
                <span class="admin-nav-label">Menu Utama</span>
                <ul class="admin-nav-list">
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                           class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="7" rx="1"/>
                                <rect x="14" y="3" width="7" height="7" rx="1"/>
                                <rect x="14" y="14" width="7" height="7" rx="1"/>
                                <rect x="3" y="14" width="7" height="7" rx="1"/>
                            </svg>
                            Overview Statistik
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.media.index') }}"
                           class="admin-nav-link {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="2" width="20" height="20" rx="2"/>
                                <line x1="7" y1="2" x2="7" y2="22"/>
                                <line x1="17" y1="2" x2="17" y2="22"/>
                                <line x1="2" y1="12" x2="22" y2="12"/>
                            </svg>
                            Kelola Film &amp; TV
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.media.create') }}" class="admin-nav-link admin-nav-add">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Tambah Media Baru
                        </a>
                    </li>
                </ul>
            </nav>

            {{-- User Info + Logout --}}
            <div class="admin-user-panel">
                <div class="admin-user-info">
                    <strong>{{ auth()->user()->name ?? 'Admin' }}</strong>
                    <small>{{ auth()->user()->email ?? '' }}</small>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-outline-sm btn-danger-sm" style="width: 100%; justify-content: center;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>

        </aside>

        {{-- ===== MAIN CONTENT ===== --}}
        <main class="admin-content">

            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom: 1.5rem;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger" style="margin-bottom: 1.5rem;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @yield('content')

        </main>

    </div>{{-- .admin-wrapper --}}

    <script src="{{ asset('js/flixora.js') }}"></script>
</body>
</html>

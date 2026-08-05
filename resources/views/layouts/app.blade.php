<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Flixora - Rekomendasi Film & TV Show Terlengkap')</title>

    {{-- Anti-FOUC: Apply theme before page paint --}}
    <script>
        (function(){
            var t = localStorage.getItem('flixora_theme');
            if (t === 'light') document.documentElement.setAttribute('data-theme', 'light');
        })();
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/flixora.css') }}">
    @yield('styles')
</head>
<script>
function openTrailer(url, media) {

    // Simpan ke riwayat jika data media tersedia
    if (media && media.id) {
        fetch('/history/add/' + media.id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        }).catch(error => console.error(error));
    }

    // Buka trailer
    window.open(url, '_blank');
}
</script>
<body>

    <!-- Navigation Header -->
    <nav class="glass-nav">
        <div class="navbar-container">

            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="brand-logo">
                <svg class="brand-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 4L8 12L4 20H6.5L10.5 12L6.5 4H4Z" fill="var(--accent-red)"/>
                    <path d="M10 4L14 12L10 20H12.5L16.5 12L12.5 4H10Z" fill="var(--accent-red)" opacity="0.6"/>
                    <path d="M16 4L20 12L16 20H18.5L22.5 12L18.5 4H16Z" fill="var(--accent-red)" opacity="0.3"/>
                </svg>
                FLIXORA
            </a>

            <!-- Search Form -->
            <form action="{{ route('home') }}" method="GET" class="search-bar-form">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-muted); flex-shrink:0;">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" name="q" class="search-bar-input" placeholder="Cari film, TV show, aktor..." value="{{ request('q') }}">
            </form>

            <!-- Nav Links -->
            <ul class="nav-links">
                <li><a href="{{ route('home') }}"                             class="nav-link {{ request()->is('/')       && !request('type') ? 'active' : '' }}">Beranda</a></li>
                <li><a href="{{ route('home', ['type' => 'movie']) }}"        class="nav-link {{ request('type') == 'movie'   ? 'active' : '' }}">Film</a></li>
                <li><a href="{{ route('home', ['type' => 'tv_show']) }}"      class="nav-link {{ request('type') == 'tv_show' ? 'active' : '' }}">Acara TV</a></li>
                <li><a href="{{ route('history') }}"                          class="nav-link {{ request()->is('watch-history') ? 'active' : '' }}">Riwayat</a></li>

                @auth
                    @if(auth()->user()->is_admin)
                        <li><a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->is('admin*') ? 'active' : '' }}">Dashboard</a></li>
                        <li>
                            <form action="{{ route('admin.logout') }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-outline-sm">Logout</button>
                            </form>
                        </li>
                    @endif
                @else
                    <li><a href="{{ route('admin.login') }}" class="nav-link {{ request()->is('admin/login') ? 'active' : '' }}">Login</a></li>
                @endauth

                <!-- Theme Toggle -->
                <li>
                    <button id="themeToggleBtn" class="theme-toggle-btn" title="Toggle Gelap/Terang" onclick="toggleTheme()">
                        {{-- Sun icon (shown in dark mode → click to go light) --}}
                        <svg id="iconSun" class="theme-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="5"/>
                            <line x1="12" y1="1"  x2="12" y2="3"/>
                            <line x1="12" y1="21" x2="12" y2="23"/>
                            <line x1="4.22" y1="4.22"  x2="5.64" y2="5.64"/>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                            <line x1="1"  y1="12" x2="3"  y2="12"/>
                            <line x1="21" y1="12" x2="23" y2="12"/>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                            <line x1="18.36" y1="5.64"  x2="19.78" y2="4.22"/>
                        </svg>
                        {{-- Moon icon (shown in light mode → click to go dark) --}}
                        <svg id="iconMoon" class="theme-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                        </svg>
                        <span id="themeLabel">Terang</span>
                    </button>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Body Container -->
    <main style="max-width: 1320px; margin: 0 auto; padding: 0 1.5rem 3rem 1.5rem;">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-inner">
            <p class="footer-brand">FLIXORA</p>
            <p class="footer-tagline">Platform Rekomendasi & Ulasan Film / Acara TV Interaktif</p>
            <p class="footer-copy">Sistem Rekomendasi Film Otomatis Berbasis Genre &mdash; &copy; {{ date('Y') }}</p>
        </div>
    </footer>

    <!-- Trailer Video Modal -->
    <div id="trailerModal" class="modal-backdrop">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="trailerModalTitle" style="color: #fff; font-size: 1.1rem; font-weight: 700;">Trailer Video</h3>
                <button class="modal-close" onclick="closeTrailer()">&times;</button>
            </div>
            <div class="video-container">
                <iframe id="trailerIframe" src="" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    </div>

    <!-- Custom JS -->
    <script src="{{ asset('js/flixora.js') }}"></script>
    @yield('scripts')
</body>
</html>

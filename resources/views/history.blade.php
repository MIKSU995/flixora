@extends('layouts.app')

@section('title', 'Riwayat & Favorit Saya - Flixora')

@section('content')

    <div class="page-header">
        <h1 class="page-title">Riwayat & Favorit Saya</h1>
        <p class="page-sub">Daftar ini disimpan secara pribadi di peramban Anda (tanpa perlu login).</p>
    </div>

    {{-- Section 1: Watch History --}}
    <div class="history-section">
        <div class="section-header">
            <h2 class="section-title">Terakhir Ditonton</h2>
            <button onclick="clearWatchHistory()" class="btn-outline-sm btn-danger-sm">Hapus Riwayat</button>
        </div>

        <div id="watchHistoryGrid" class="media-grid"></div>

        <div id="emptyHistoryNotice" class="glass-panel empty-state" style="display: none;">
            <p class="empty-state-title">Belum Ada Riwayat</p>
            <p class="empty-state-sub">Klik "Tonton & Tambah ke Histori" pada halaman detail film untuk mulai merekam.</p>
        </div>
    </div>

    {{-- Section 2: Favorites --}}
    <div class="history-section">
        <div class="section-header">
            <h2 class="section-title">Daftar Favorit</h2>
            <button onclick="clearFavorites()" class="btn-outline-sm btn-danger-sm">Hapus Semua</button>
        </div>

        <div id="favoritesGrid" class="media-grid"></div>

        <div id="emptyFavoritesNotice" class="glass-panel empty-state" style="display: none;">
            <p class="empty-state-title">Belum Ada Favorit</p>
            <p class="empty-state-sub">Klik "Tambah Favorit" pada halaman detail film yang Anda sukai.</p>
        </div>
    </div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    renderHistory();
    renderFavorites();
});

function renderHistory() {
    const grid   = document.getElementById('watchHistoryGrid');
    const notice = document.getElementById('emptyHistoryNotice');
    const history = JSON.parse(localStorage.getItem('flixora_watch_history') || '[]');

    if (history.length === 0) {
        grid.style.display = 'none';
        notice.style.display = 'block';
        return;
    }

    grid.style.display = 'grid';
    notice.style.display = 'none';
    grid.innerHTML = history.map(item => `
        <a href="/media/${item.slug}" class="media-card">
            <div class="card-poster-wrapper">
                <img src="${item.poster_url}" alt="${item.title}" class="card-poster-img">
                <div class="rating-badge">${parseFloat(item.avg_rating).toFixed(1)}</div>
            </div>
            <div class="card-content">
                <h3 class="card-title">${item.title}</h3>
                <div class="card-meta">
                    <span>${item.release_year}</span> &bull;
                    <span>${item.type === 'movie' ? 'Film' : 'TV Show'}</span>
                </div>
                <small class="card-watched-at">Ditonton: ${item.watched_at || 'Baru Saja'}</small>
            </div>
        </a>
    `).join('');
}

function renderFavorites() {
    const grid   = document.getElementById('favoritesGrid');
    const notice = document.getElementById('emptyFavoritesNotice');
    const favorites = JSON.parse(localStorage.getItem('flixora_favorites') || '[]');

    if (favorites.length === 0) {
        grid.style.display = 'none';
        notice.style.display = 'block';
        return;
    }

    grid.style.display = 'grid';
    notice.style.display = 'none';
    grid.innerHTML = favorites.map(item => `
        <a href="/media/${item.slug}" class="media-card">
            <div class="card-poster-wrapper">
                <img src="${item.poster_url}" alt="${item.title}" class="card-poster-img">
                <div class="rating-badge">${parseFloat(item.avg_rating).toFixed(1)}</div>
            </div>
            <div class="card-content">
                <h3 class="card-title">${item.title}</h3>
                <div class="card-meta">
                    <span>${item.release_year}</span> &bull;
                    <span>${item.type === 'movie' ? 'Film' : 'TV Show'}</span>
                </div>
                <span class="badge badge-red" style="margin-top: auto; align-self: flex-start; font-size: 0.65rem;">Favorit</span>
            </div>
        </a>
    `).join('');
}

function clearWatchHistory() {
    if (confirm('Hapus seluruh riwayat tontonan?')) {
        localStorage.removeItem('flixora_watch_history');
        renderHistory();
    }
}

function clearFavorites() {
    if (confirm('Hapus seluruh daftar favorit Anda?')) {
        localStorage.removeItem('flixora_favorites');
        renderFavorites();
    }
}
</script>
@endsection

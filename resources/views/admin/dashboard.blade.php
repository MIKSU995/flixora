@extends('layouts.admin')

@section('title', 'Admin Dashboard - Flixora')

@section('content')

    {{-- Page Header --}}
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.3rem;">Overview Dashboard</h1>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Pusat kontrol manajemen katalog Flixora.</p>
    </div>

    {{-- Stats Grid --}}
    <div class="admin-stats-grid">
        <div class="admin-stat-card" style="border-left: 3px solid var(--accent-red);">
            <div class="admin-stat-label">Total Film</div>
            <div class="admin-stat-value">{{ $totalMovies }}</div>
        </div>
        <div class="admin-stat-card" style="border-left: 3px solid var(--accent-cyan);">
            <div class="admin-stat-label">Total Acara TV</div>
            <div class="admin-stat-value">{{ $totalTvShows }}</div>
        </div>
        <div class="admin-stat-card" style="border-left: 3px solid var(--accent-gold);">
            <div class="admin-stat-label">Rating User</div>
            <div class="admin-stat-value">{{ $totalRatings }}</div>
        </div>
        <div class="admin-stat-card" style="border-left: 3px solid #10b981;">
            <div class="admin-stat-label">Komentar Ulasan</div>
            <div class="admin-stat-value">{{ $totalComments }}</div>
        </div>
    </div>

    {{-- Bottom Grid: Recent Media & Comments --}}
    <div style="display: grid; grid-template-columns: 3fr 2fr; gap: 2rem;">

        {{-- Left: Recent Media --}}
        <div class="glass-panel" style="padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <h3 style="font-size: 1rem; font-weight: 700; color: var(--text-main);">Media Terbaru</h3>
                <a href="{{ route('admin.media.index') }}" class="nav-link" style="font-size: 0.8rem;">Kelola Semua →</a>
            </div>
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Poster</th>
                            <th>Judul &amp; Tipe</th>
                            <th>Tahun</th>
                            <th>Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentMedia as $m)
                            <tr>
                                <td>
                                    <img src="{{ $m->poster_url }}" alt="{{ $m->title }}" style="width: 40px; height: 56px; object-fit: cover; border-radius: 6px; border: 1px solid var(--border-glass);">
                                </td>
                                <td>
                                    <strong style="color: var(--text-main); display: block; font-size: 0.9rem;">{{ $m->title }}</strong>
                                    <small style="color: var(--text-muted); font-size: 0.75rem;">{{ $m->type == 'movie' ? 'Film' : 'TV Show' }}</small>
                                </td>
                                <td style="font-size: 0.85rem; color: var(--text-muted);">{{ $m->release_year }}</td>
                                <td><span class="badge badge-gold">{{ number_format($m->avg_rating, 1) }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Right: Recent Comments --}}
        <div class="glass-panel" style="padding: 1.5rem;">
            <h3 style="font-size: 1rem; font-weight: 700; color: var(--text-main); margin-bottom: 1.25rem;">Ulasan Terbaru</h3>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @forelse($recentComments as $comm)
                    <div style="background: rgba(0,0,0,0.25); border: 1px solid var(--border-glass); padding: 0.85rem 1rem; border-radius: 10px;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.3rem; gap: 0.5rem;">
                            <strong style="color: var(--accent-gold); font-size: 0.85rem;">{{ $comm->user_name }}</strong>
                            <small style="color: var(--text-muted); font-size: 0.72rem; white-space: nowrap;">{{ $comm->created_at->diffForHumans() }}</small>
                        </div>
                        <small style="color: var(--accent-cyan); display: block; margin-bottom: 0.3rem; font-size: 0.78rem;">{{ $comm->media->title ?? '-' }}</small>
                        <p style="font-size: 0.83rem; color: #d1d5db; margin-bottom: 0.5rem; line-height: 1.5;">"{{ Str::limit($comm->comment_text, 90) }}"</p>
                        <form action="{{ route('admin.comments.destroy', $comm->id) }}" method="POST" onsubmit="return confirm('Hapus komentar ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete-comment">Hapus Komentar</button>
                        </form>
                    </div>
                @empty
                    <p class="empty-comments-msg">Belum ada komentar.</p>
                @endforelse
            </div>
        </div>

    </div>

@endsection

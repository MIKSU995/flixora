@extends('layouts.admin')

@section('title', 'Kelola Media Film & Acara TV - Admin Flixora')

@section('content')

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1 style="font-size: 1.8rem; font-weight: 800; color: #fff; margin-bottom: 0.3rem;">Manajemen Film & Acara TV</h1>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Daftar lengkap katalog media di platform Flixora.</p>
        </div>
        <a href="{{ route('admin.media.create') }}" class="btn-primary">
            + Tambah Film / TV Show Baru
        </a>
    </div>

    <!-- Filter & Search Form -->
    <div class="glass-panel" style="padding: 1.2rem; margin-bottom: 1.5rem;">
        <form action="{{ route('admin.media.index') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <input type="text" name="q" class="form-control" placeholder="Cari berdasarkan judul..." value="{{ request('q') }}" style="max-width: 300px;">
            <select name="type" class="form-control" style="max-width: 180px;">
                <option value="">-- Semua Tipe --</option>
                <option value="movie" {{ request('type') == 'movie' ? 'selected' : '' }}>Film</option>
                <option value="tv_show" {{ request('type') == 'tv_show' ? 'selected' : '' }}>Acara TV</option>
            </select>
            <button type="submit" class="btn-secondary" style="padding: 0.6rem 1.2rem;">Cari</button>
            @if(request('q') || request('type'))
                <a href="{{ route('admin.media.index') }}" class="btn-secondary" style="padding: 0.6rem 1.2rem; color: #f87171;">Reset</a>
            @endif
        </form>
    </div>

    <!-- Media Catalog Table -->
    <div class="glass-panel" style="padding: 1.5rem;">
        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Poster</th>
                        <th>Judul Media</th>
                        <th>Tipe</th>
                        <th>Tahun</th>
                        <th>Genre</th>
                        <th>Rating User</th>
                        <th>Aksi Management</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mediaItems as $item)
                        <tr>
                            <td>
                                <img src="{{ $item->poster_url }}" alt="{{ $item->title }}" style="width: 45px; height: 65px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-glass);">
                            </td>
                            <td>
                                <strong style="color: #fff; font-size: 0.95rem; display: block;">{{ $item->title }}</strong>
                                <small style="color: var(--text-muted); font-size: 0.75rem;">Sutradara: {{ $item->director ?? '-' }}</small>
                            </td>
                            <td>
                                <span class="badge {{ $item->type == 'movie' ? 'badge-red' : 'badge-cyan' }}">
                                    {{ $item->type == 'movie' ? 'FILM' : 'TV SHOW' }}
                                </span>
                            </td>
                            <td style="font-size: 0.9rem; color: #d1d5db;">{{ $item->release_year }}</td>
                            <td>
                                <div style="display: flex; flex-wrap: wrap; gap: 0.2rem;">
                                    @foreach($item->genres as $g)
                                        <span class="genre-tag">{{ $g->name }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-gold">{{ number_format($item->avg_rating, 1) }} ({{ $item->total_ratings }})</span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="{{ route('admin.media.edit', $item->id) }}" class="btn-secondary" style="font-size: 0.8rem; padding: 0.3rem 0.7rem;">
                                        Edit
                                    </a>
                                    
                                    <form action="{{ route('admin.media.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus {{ $item->title }} dari database?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-secondary" style="font-size: 0.8rem; padding: 0.3rem 0.7rem; color: #f87171; border-color: rgba(239,68,68,0.3);">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                                Belum ada data media dalam katalog. Klik tombol "Tambah Film / TV Show Baru" di atas!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="display: flex; justify-content: center; margin-top: 1.5rem;">
            {{ $mediaItems->links() }}
        </div>
    </div>

@endsection

@extends('layouts.admin')

@section('title', 'Tambah Film / TV Show Baru - Admin Flixora')

@section('content')

    <div style="margin-bottom: 2rem;">
        <a href="{{ route('admin.media.index') }}" class="nav-link" style="font-size: 0.85rem; margin-bottom: 0.5rem; display: inline-block;">← Kembali ke Daftar Media</a>
        <h1 style="font-size: 1.8rem; font-weight: 800; color: #fff; margin-bottom: 0.3rem;">Tambah Film / Acara TV Baru</h1>
        <p style="color: var(--text-muted); font-size: 0.9rem;">Lengkapi informasi detail media untuk dimasukkan ke dalam katalog Flixora.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <div>
                <strong>Terdapat kesalahan pengisian form:</strong>
                <ul style="margin-top: 0.4rem; padding-left: 1.2rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="glass-panel" style="padding: 2rem; max-width: 900px;">
        <form action="{{ route('admin.media.store') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; flex-wrap: wrap;">
                <div class="form-group">
                    <label for="title" class="form-label">Judul Film / TV Show *</label>
                    <input type="text" id="title" name="title" class="form-control" placeholder="Contoh: Oppenheimer" value="{{ old('title') }}" required>
                </div>

                <div class="form-group">
                    <label for="type" class="form-label">Tipe Media *</label>
                    <select id="type" name="type" class="form-control" required>
                        <option value="movie" {{ old('type') == 'movie' ? 'selected' : '' }}>Film (Movie)</option>
                        <option value="tv_show" {{ old('type') == 'tv_show' ? 'selected' : '' }}>Acara TV (TV Show)</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label for="release_year" class="form-label">Tahun Rilis *</label>
                    <input type="number" id="release_year" name="release_year" class="form-control" placeholder="2024" value="{{ old('release_year', date('Y')) }}" required>
                </div>

                <div class="form-group">
                    <label for="duration_or_seasons" class="form-label">Durasi / Season *</label>
                    <input type="text" id="duration_or_seasons" name="duration_or_seasons" class="form-control" placeholder="Contoh: 2j 30m atau 3 Season" value="{{ old('duration_or_seasons') }}" required>
                </div>
            </div>

            <!-- Genre Selection (Multi-select checkboxes) -->
            <div class="form-group">
                <label class="form-label">Pilih Genre (Minimal 1) *</label>
                <div style="display: flex; flex-wrap: wrap; gap: 0.8rem; background: rgba(0,0,0,0.3); padding: 1rem; border-radius: 10px; border: 1px solid var(--border-glass);">
                    @foreach($genres as $g)
                        <label style="display: flex; align-items: center; gap: 0.4rem; font-size: 0.85rem; color: #d1d5db; cursor: pointer;">
                            <input type="checkbox" name="genres[]" value="{{ $g->id }}" {{ is_array(old('genres')) && in_array($g->id, old('genres')) ? 'checked' : '' }} style="accent-color: var(--accent-red);">
                            {{ $g->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Sinopsis / Deskripsi Singkat *</label>
                <textarea id="description" name="description" class="form-control" placeholder="Tuliskan gambaran jalan cerita film..." required>{{ old('description') }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label for="poster_url" class="form-label">URL Poster Gambar *</label>
                    <input type="url" id="poster_url" name="poster_url" class="form-control" placeholder="https://images.unsplash.com/photo-..." value="{{ old('poster_url') }}" required>
                    <small style="color: var(--text-muted); font-size: 0.75rem;">Gunakan tautan gambar poster beresolusi tinggi (misal dari Unsplash/TMDB).</small>
                </div>

                <div class="form-group">
                    <label for="banner_url" class="form-label">URL Banner Backdrop (Opsional)</label>
                    <input type="url" id="banner_url" name="banner_url" class="form-control" placeholder="https://images.unsplash.com/photo-..." value="{{ old('banner_url') }}">
                </div>
            </div>

            <div class="form-group">
                <label for="trailer_url" class="form-label">URL Embed Video Trailer YouTube (Opsional)</label>
                <input type="url" id="trailer_url" name="trailer_url" class="form-control" placeholder="https://www.youtube.com/embed/XXXXXX" value="{{ old('trailer_url') }}">
                <small style="color: var(--text-muted); font-size: 0.75rem;">Format embed YouTube: https://www.youtube.com/embed/VIDEO_ID</small>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label for="director" class="form-label">Sutradara (Director)</label>
                    <input type="text" id="director" name="director" class="form-control" placeholder="Contoh: Christopher Nolan" value="{{ old('director') }}">
                </div>

                <div class="form-group">
                    <label for="cast" class="form-label">Pemeran Utama (Cast)</label>
                    <input type="text" id="cast" name="cast" class="form-control" placeholder="Contoh: Cillian Murphy, Emily Blunt" value="{{ old('cast') }}">
                </div>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn-primary" style="padding: 0.8rem 2rem;">Simpan Ke Katalog</button>
                <a href="{{ route('admin.media.index') }}" class="btn-secondary" style="padding: 0.8rem 1.5rem;">Batal</a>
            </div>
        </form>
    </div>

@endsection

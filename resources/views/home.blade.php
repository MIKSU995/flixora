@extends('layouts.app')

@section('title', 'Flixora - Beranda Rekomendasi Film & Acara TV')

@section('content')

    {{-- Search / Filter Active Header --}}
    @if($searchQuery || $selectedGenreSlug || $selectedType)
        <div class="filter-notice glass-panel">
            <div>
                <h2 class="filter-notice-title">
                    Hasil Filter
                    @if($searchQuery)  — Pencarian: <span style="color:var(--accent-gold);">"{{ $searchQuery }}"</span>@endif
                    @if($selectedType) — Tipe: <span style="color:var(--accent-cyan);">{{ $selectedType == 'movie' ? 'Film' : 'Acara TV' }}</span>@endif
                    @if($selectedGenreSlug) — Genre: <span style="color:var(--accent-red);">{{ ucfirst($selectedGenreSlug) }}</span>@endif
                </h2>
                <p class="filter-notice-sub">Ditemukan {{ $catalogMedia->total() }} judul media.</p>
            </div>
            <a href="{{ route('home') }}" class="btn-outline-sm">Reset Filter</a>
        </div>
    @else
        {{-- Hero Banner --}}
        @if($featuredMedia)
            <div class="hero-banner" style="background-image: url('{{ $featuredMedia->banner_url ?? $featuredMedia->poster_url }}');">
                <div class="hero-overlay"></div>
                <div class="hero-content">
                    <div class="hero-badges">
                        <span class="badge badge-red">{{ $featuredMedia->type == 'movie' ? 'FILM TERPOPULER' : 'TV SHOW TERPOPULER' }}</span>
                        <span class="badge badge-gold">{{ number_format($featuredMedia->avg_rating, 1) }} / 5.0</span>
                        <span class="hero-year">{{ $featuredMedia->release_year }}</span>
                    </div>
                    <h1 class="hero-title">{{ $featuredMedia->title }}</h1>
                    <p class="hero-description">{{ $featuredMedia->description }}</p>
                    <div class="hero-actions">
                        <a href="{{ route('media.show', $featuredMedia->slug) }}" class="btn-primary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                            Lihat Detail
                        </a>
                        @if($featuredMedia->trailer_url)
                            <button onclick="openTrailer('{{ $featuredMedia->trailer_url }}', '{{ $featuredMedia->title }}', {{ $featuredMedia->id }})" class="btn-secondary">
                                Tonton Trailer
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Auto Genre Recommendation --}}
        <div class="rec-panel glass-panel">
            <div class="section-header">
                <div>
                    <h2 class="section-title" style="color: var(--white--);">Rekomendasi Berdasarkan Genre</h2>
                    <p class="section-sub">Judul terbaik dari genre favorit & histori tontonan terakhir Anda.</p>
                </div>
            </div>
            <div class="media-grid" style="grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)); margin-bottom: 0;">
                @foreach($topRatedMedia as $recItem)
                    <a href="{{ route('media.show', $recItem->slug) }}" class="media-card">
                        <div class="card-poster-wrapper">
                            <img src="{{ $recItem->poster_url }}" alt="{{ $recItem->title }}" class="card-poster-img" loading="lazy">
                            <div class="rating-badge">{{ number_format($recItem->avg_rating, 1) }}</div>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title">{{ $recItem->title }}</h3>
                            <div class="card-meta">
                                <span>{{ $recItem->release_year }}</span> &bull;
                                <span>{{ $recItem->type == 'movie' ? 'Film' : 'TV Show' }}</span>
                            </div>
                            <div class="card-genres">
                                @foreach($recItem->genres->take(2) as $g)
                                    <span class="genre-tag">{{ $g->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Catalog Header & Filter Tabs --}}
    <div class="catalog-header">
        <div class="catalog-title-row">
            <h2 class="section-title">Katalog Film & Acara TV</h2>
            <span class="catalog-total">{{ $catalogMedia->total() }} Judul</span>
        </div>
        <div class="filter-tabs">
            <a href="{{ route('home') }}"                              class="filter-tab {{ !request('genre') && !request('type') ? 'active' : '' }}">Semua</a>
            <a href="{{ route('home', ['type' => 'movie']) }}"        class="filter-tab {{ request('type') == 'movie'   ? 'active' : '' }}">Film</a>
            <a href="{{ route('home', ['type' => 'tv_show']) }}"      class="filter-tab {{ request('type') == 'tv_show' ? 'active' : '' }}">Acara TV</a>
            @foreach($allGenres as $genre)
                <a href="{{ route('home', array_merge(request()->query(), ['genre' => $genre->slug])) }}"
                   class="filter-tab {{ request('genre') == $genre->slug ? 'active' : '' }}">
                    {{ $genre->name }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Main Catalog Grid --}}
    @if($catalogMedia->count() > 0)
        <div class="media-grid">
            @foreach($catalogMedia as $item)
                <a href="{{ route('media.show', $item->slug) }}" class="media-card">
                    <div class="card-poster-wrapper">
                        <img src="{{ $item->poster_url }}" alt="{{ $item->title }}" class="card-poster-img" loading="lazy">
                        <div class="rating-badge">{{ number_format($item->avg_rating, 1) }}</div>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">{{ $item->title }}</h3>
                        <div class="card-meta">
                            <span>{{ $item->release_year }}</span> &bull;
                            <span>{{ $item->type == 'movie' ? 'Film' : 'TV Show' }}</span>
                        </div>
                        <div class="card-genres">
                            @foreach($item->genres->take(3) as $g)
                                <span class="genre-tag">{{ $g->name }}</span>
                            @endforeach
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="pagination-wrap">
            {{ $catalogMedia->links() }}
        </div>
    @else
        <div class="glass-panel empty-state">
            <p class="empty-state-title">Tidak Ada Hasil Ditemukan</p>
            <p class="empty-state-sub">Coba cari dengan kata kunci atau filter genre yang berbeda.</p>
            <a href="{{ route('home') }}" class="btn-primary" style="margin-top: 1.2rem;">Kembali ke Beranda</a>
        </div>
    @endif

@endsection

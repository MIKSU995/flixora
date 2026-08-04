@extends('layouts.app')

@section('title', $media->title . ' - Detail & Ulasan Flixora')

@section('content')

    {{-- Media Header --}}
    <div class="glass-panel media-detail-header">
        {{-- Blurred backdrop --}}
        <div class="media-detail-backdrop" style="background-image: url('{{ $media->banner_url ?? $media->poster_url }}');"></div>

        <div class="media-detail-body">
            {{-- Poster --}}
            <div class="media-detail-poster">
                <img src="{{ $media->poster_url }}" alt="{{ $media->title }}">
            </div>

            {{-- Info --}}
            <div class="media-detail-info">
                <div class="media-detail-badges">
                    <span class="badge badge-red">{{ $media->type == 'movie' ? 'FILM' : 'ACARA TV' }}</span>
                    <span class="badge badge-gold">
                        <span id="avgRatingValue">{{ number_format($media->avg_rating, 1) }}</span> / 5.0
                        (<span id="totalRatingCount">{{ $media->total_ratings }}</span> ulasan)
                    </span>
                    <span class="media-detail-year">{{ $media->release_year }} &bull; {{ $media->duration_or_seasons }}</span>
                </div>

                <h1 class="media-detail-title">{{ $media->title }}</h1>

                <div class="media-detail-genres">
                    @foreach($media->genres as $g)
                        <a href="{{ route('home', ['genre' => $g->slug]) }}" class="badge badge-cyan" style="text-decoration: none;">{{ $g->name }}</a>
                    @endforeach
                </div>

                <p class="media-detail-desc">{{ $media->description }}</p>

                <div class="media-detail-crew">
                    <div class="crew-row">
                        <span class="crew-label">Sutradara</span>
                        <span class="crew-value">{{ $media->director ?? 'Belum tersedia' }}</span>
                    </div>
                    <div class="crew-row">
                        <span class="crew-label">Pemeran Utama</span>
                        <span class="crew-value">{{ $media->cast ?? 'Belum tersedia' }}</span>
                    </div>
                </div>

                <div class="media-detail-actions">
                    @if($media->trailer_url)
                        <button
                        onclick="openTrailer('{{ $media->trailer_url }}',{{ $media->title }}, {{ $media->id }})">
                        Tonton Trailer
                    </button>
                    @endif

                    <button onclick="markAsWatched({{ json_encode(['id' => $media->id, 'title' => $media->title, 'slug' => $media->slug, 'poster_url' => $media->poster_url, 'type' => $media->type, 'release_year' => $media->release_year, 'avg_rating' => $media->avg_rating, 'genres' => $media->genres->toArray()]) }})" class="btn-secondary">
                        Tambah ke Riwayat
                    </button>

                    <button onclick="toggleFavorite({{ json_encode(['id' => $media->id, 'title' => $media->title, 'slug' => $media->slug, 'poster_url' => $media->poster_url, 'type' => $media->type, 'release_year' => $media->release_year, 'avg_rating' => $media->avg_rating]) }}, this)" class="btn-secondary" id="favBtn">
                        Tambah Favorit
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Rating & Comment Grid --}}
    <div class="detail-bottom-grid">

        {{-- Rating Widget --}}
        <div class="glass-panel rating-widget">
            <h3 class="widget-title">Beri Rating</h3>
            <p class="widget-sub">Penilaian Anda sangat penting bagi pengguna lain.</p>

            <div id="starRatingBox" class="star-rating-box" data-media-id="{{ $media->id }}" data-user-rating="{{ $userRating ?? 0 }}">
                @for($i = 1; $i <= 5; $i++)
                    <span class="star-icon {{ ($userRating ?? 0) >= $i ? 'active' : '' }}" data-value="{{ $i }}">&#9733;</span>
                @endfor
            </div>

            <p class="rating-hint">
                {{ $userRating ? "Rating Anda: {$userRating} dari 5 bintang" : "Klik bintang untuk memberikan nilai." }}
            </p>
        </div>

        {{-- Comments --}}
        <div class="glass-panel comments-panel">
            <h3 class="widget-title">Ulasan & Komentar <span class="comment-count">({{ $media->comments->count() }})</span></h3>
            <p class="widget-sub">Tuliskan ulasan atau pendapat Anda tentang judul ini.</p>

            {{-- Comment Form --}}
            <form id="commentForm" data-media-id="{{ $media->id }}" class="comment-form">
                <div class="form-group">
                    <label for="commentUserName" class="form-label">Nama / Nickname *</label>
                    <input type="text" id="commentUserName" class="form-control" placeholder="Contoh: Budi_MovieLover" required>
                </div>
                <div class="form-group">
                    <label for="commentText" class="form-label">Ulasan Anda *</label>
                    <textarea id="commentText" class="form-control" placeholder="Bagaimana pendapat Anda tentang film ini?" required></textarea>
                </div>
                <button type="submit" id="commentSubmitBtn" class="btn-primary" style="font-size: 0.85rem;">
                    Kirim Ulasan
                </button>
            </form>

            {{-- Comment List --}}
            <div id="commentsList">
                @forelse($media->comments as $comment)
                    <div class="comment-item glass-panel">
                        <div class="comment-header">
                            <strong class="comment-author">{{ $comment->user_name }}</strong>
                            <div class="comment-meta-right">
                                <small class="comment-time">{{ $comment->created_at->diffForHumans() }}</small>
                                @auth
                                    @if(auth()->user()->is_admin)
                                        <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Hapus komentar ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete-comment">Hapus</button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                        <p class="comment-text">{{ $comment->comment_text }}</p>
                    </div>
                @empty
                    <p id="emptyCommentsNotice" class="empty-comments-msg">Belum ada ulasan. Jadilah yang pertama!</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recommendations --}}
    <div class="rec-section">
        <div class="section-header">
            <div>
                <h2 class="section-title">Film Sejenis</h2>
                <p class="section-sub">
                    Rekomendasi berdasarkan genre
                    <span style="color: var(--accent-cyan);">({{ $media->genres->pluck('name')->implode(', ') }})</span>
                    diurutkan dari rating tertinggi.
                </p>
            </div>
            <span class="badge badge-gold">Genre Match</span>
        </div>

        <div class="media-grid">
            @foreach($recommendedMedia as $rec)
                <a href="{{ route('media.show', $rec->slug) }}" class="media-card">
                    <div class="card-poster-wrapper">
                        <img src="{{ $rec->poster_url }}" alt="{{ $rec->title }}" class="card-poster-img" loading="lazy">
                        <div class="rating-badge">{{ number_format($rec->avg_rating, 1) }}</div>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">{{ $rec->title }}</h3>
                        <div class="card-meta">
                            <span>{{ $rec->release_year }}</span> &bull;
                            <span>{{ $rec->type == 'movie' ? 'Film' : 'TV Show' }}</span>
                        </div>
                        <div class="card-genres">
                            @foreach($rec->genres->take(3) as $g)
                                <span class="genre-tag">{{ $g->name }}</span>
                            @endforeach
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

@endsection

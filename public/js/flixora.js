/**
 * Flixora Interactive JS Engine
 * Handles AJAX Star Ratings, Comments, Watch History & Trailer Modal Player
 */

document.addEventListener('DOMContentLoaded', () => {
    // CSRF Token setup for AJAX
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // --- 1. Star Rating Interactive System ---
    const starContainer = document.getElementById('starRatingBox');
    if (starContainer) {
        const stars = starContainer.querySelectorAll('.star-icon');
        const mediaId = starContainer.dataset.mediaId;
        const currentRatingDisplay = document.getElementById('avgRatingValue');
        const totalRatingDisplay = document.getElementById('totalRatingCount');

        stars.forEach(star => {
            star.addEventListener('mouseover', function () {
                const hoverValue = parseInt(this.dataset.value);
                highlightStars(hoverValue);
            });

            star.addEventListener('mouseleave', function () {
                const activeValue = parseInt(starContainer.dataset.userRating || 0);
                highlightStars(activeValue);
            });

            star.addEventListener('click', function () {
                const ratingValue = parseInt(this.dataset.value);

                fetch(`/media/${mediaId}/rate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ rating: ratingValue })
                })
                .then(res => res.json())
                .then(data => {
    if (data.success) {

        starContainer.dataset.userRating = data.user_rating;
        highlightStars(data.user_rating);

        if (currentRatingDisplay) {
            currentRatingDisplay.innerText = data.avg_rating;
        }

        if (totalRatingDisplay) {
            totalRatingDisplay.innerText = data.total_ratings;
        }

        const badge = document.querySelector('.badge-gold');
        if (badge) {
            badge.innerHTML = `
                <span id="avgRatingValue">${data.avg_rating}</span> / 5.0
                (<span id="totalRatingCount">${data.total_ratings}</span> ulasan)
            `;
        }

        document.querySelectorAll('.rating-badge').forEach(function(el){
            if(el.dataset.mediaId == mediaId){
                el.innerText = data.avg_rating;
            }
        });

        showToast(data.message, 'success');

    } else {
        showToast('Gagal mengirim rating.', 'danger');
    }
})
                .catch(err => {
                    console.error(err);
                    showToast('Terjadi kesalahan koneksi.', 'danger');
                });
            });
        });

        function highlightStars(val) {
            stars.forEach(s => {
                const sVal = parseInt(s.dataset.value);
                if (sVal <= val) {
                    s.classList.add('active');
                } else {
                    s.classList.remove('active');
                }
            });
        }
    }

    // --- 2. AJAX Comment Submission System ---
    const commentForm = document.getElementById('commentForm');
    if (commentForm) {
        commentForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const mediaId = this.dataset.mediaId;
            const userName = document.getElementById('commentUserName').value.trim();
            const commentText = document.getElementById('commentText').value.trim();
            const commentSubmitBtn = document.getElementById('commentSubmitBtn');

            if (!userName || !commentText) {
                showToast('Harap isi nama dan komentar Anda.', 'danger');
                return;
            }

            commentSubmitBtn.disabled = true;
            commentSubmitBtn.innerText = 'Mengirim...';

            fetch(`/media/${mediaId}/comment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    user_name: userName,
                    comment_text: commentText
                })
            })
            .then(res => res.json())
            .then(data => {
                commentSubmitBtn.disabled = false;
                commentSubmitBtn.innerText = 'Kirim Ulasan';

                if (data.success) {
                    document.getElementById('commentText').value = '';
                    appendNewComment(data.comment);
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message || 'Gagal mengirim komentar.', 'danger');
                }
            })
            .catch(err => {
                console.error(err);
                commentSubmitBtn.disabled = false;
                commentSubmitBtn.innerText = 'Kirim Ulasan';
                showToast('Terjadi kesalahan koneksi.', 'danger');
            });
        });
    }

    function appendNewComment(comment) {
        const commentList = document.getElementById('commentsList');
        const emptyNotice = document.getElementById('emptyCommentsNotice');
        if (emptyNotice) emptyNotice.remove();

        const commentEl = document.createElement('div');
        commentEl.className = 'glass-panel';
        commentEl.style.padding = '1.2rem';
        commentEl.style.marginBottom = '1rem';
        commentEl.innerHTML = `
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.4rem;">
                <strong style="color: var(--accent-gold); font-size: 0.95rem;">👤 ${comment.user_name}</strong>
                <small style="color: var(--text-muted); font-size: 0.8rem;">${comment.created_at}</small>
            </div>
            <p style="color: #e5e7eb; font-size: 0.9rem;">${comment.comment_text}</p>
        `;
        commentList.prepend(commentEl);
    }

    // --- 3. Trailer Video Modal Window ---
    const trailerModal = document.getElementById('trailerModal');
    const trailerFrame = document.getElementById('trailerIframe');
    
    window.openTrailer = function (videoUrl, mediaData) {

    // Simpan otomatis ke Riwayat Ditonton
    markAsWatched(mediaData);

    if (!trailerModal || !trailerFrame) return;

    trailerFrame.src = videoUrl + '?autoplay=1';

    document.getElementById('trailerModalTitle').innerText = mediaData.title;

    trailerModal.classList.add('show');
};

    window.closeTrailer = function () {
        if (!trailerModal || !trailerFrame) return;
        trailerFrame.src = '';
        trailerModal.classList.remove('show');
    };

    if (trailerModal) {
        trailerModal.addEventListener('click', (e) => {
            if (e.target === trailerModal) closeTrailer();
        });
    }

    // --- 4. Watch History & Favorites Tracking (LocalStorage & Cookies) ---
    window.markAsWatched = function (mediaData) {
        let history = JSON.parse(localStorage.getItem('flixora_watch_history') || '[]');
        history = history.filter(item => item.id !== mediaData.id);
        history.unshift({
            id: mediaData.id,
            title: mediaData.title,
            slug: mediaData.slug,
            poster_url: mediaData.poster_url,
            type: mediaData.type,
            release_year: mediaData.release_year,
            avg_rating: mediaData.avg_rating,
            watched_at: new Date().toLocaleString('id-ID')
        });
        // Keep max 20 items
        if (history.length > 20) history.pop();
        localStorage.setItem('flixora_watch_history', JSON.stringify(history));

        // Save last watched genre in cookie to power Automatic Home Recommendation!
        if (mediaData.genres && mediaData.genres.length > 0) {
            document.cookie = `last_watched_genre=${mediaData.genres[0].slug}; path=/; max-age=2592000`;
        }

        showToast(`"${mediaData.title}" ditambahkan ke Film Terakhir Ditonton!`, 'success');
    };

    window.toggleFavorite = function (mediaData, btnElement) {
        let favorites = JSON.parse(localStorage.getItem('flixora_favorites') || '[]');
        const existsIndex = favorites.findIndex(item => item.id === mediaData.id);

        if (existsIndex > -1) {
            favorites.splice(existsIndex, 1);
            if (btnElement) btnElement.innerHTML = '♡ Tambah Favorit';
            showToast(`"${mediaData.title}" dihapus dari favorit.`, 'secondary');
        } else {
            favorites.push({
                id: mediaData.id,
                title: mediaData.title,
                slug: mediaData.slug,
                poster_url: mediaData.poster_url,
                type: mediaData.type,
                release_year: mediaData.release_year,
                avg_rating: mediaData.avg_rating
            });
            if (btnElement) btnElement.innerHTML = '♥ Favorit Saya';
            showToast(`"${mediaData.title}" berhasil ditambah ke favorit!`, 'success');
        }
        localStorage.setItem('flixora_favorites', JSON.stringify(favorites));
    };

    // Helper Toast Notifications
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type}`;
        toast.style.position = 'fixed';
        toast.style.bottom = '20px';
        toast.style.right = '20px';
        toast.style.zIndex = '3000';
        toast.style.boxShadow = '0 10px 25px rgba(0,0,0,0.5)';
        toast.innerHTML = `<span>${message}</span>`;

        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.transition = 'opacity 0.5s ease';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }
});

/* ============================
   DARK / LIGHT THEME TOGGLE
   Persisted via localStorage
   ============================ */
function toggleTheme() {
    const html     = document.documentElement;
    const isLight  = html.getAttribute('data-theme') === 'light';
    const newTheme = isLight ? 'dark' : 'light';

    if (newTheme === 'light') {
        html.setAttribute('data-theme', 'light');
    } else {
        html.removeAttribute('data-theme');
    }

    localStorage.setItem('flixora_theme', newTheme);
    updateThemeUI(newTheme);
}

function updateThemeUI(theme) {
    const sunIcon  = document.getElementById('iconSun');
    const moonIcon = document.getElementById('iconMoon');
    const label    = document.getElementById('themeLabel');

    if (!sunIcon || !moonIcon || !label) return;

    if (theme === 'light') {
        // Currently light → show moon icon → clicking goes dark
        sunIcon.style.display  = 'none';
        moonIcon.style.display = 'block';
        label.textContent      = 'Gelap';
    } else {
        // Currently dark → show sun icon → clicking goes light
        sunIcon.style.display  = 'block';
        moonIcon.style.display = 'none';
        label.textContent      = 'Terang';
    }
}

// Apply saved theme immediately on page load (before paint)
(function () {
    const saved = localStorage.getItem('flixora_theme') || 'dark';
    if (saved === 'light') {
        document.documentElement.setAttribute('data-theme', 'light');
    } else {
        document.documentElement.removeAttribute('data-theme');
    }
    // Update button UI after DOM is ready
    document.addEventListener('DOMContentLoaded', function () {
        updateThemeUI(saved);
    });
})();

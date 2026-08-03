<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Genre;
use App\Models\Media;
use App\Models\Rating;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Admin User
        User::firstOrCreate(
            ['email' => 'admin@flixora.com'],
            [
                'name' => 'Administrator Flixora',
                'password' => Hash::make('password123'),
                'is_admin' => true,
            ]
        );

        // 2. Create Master Genres
        $genresData = [
            ['name' => 'Action', 'slug' => 'action'],
            ['name' => 'Sci-Fi', 'slug' => 'sci-fi'],
            ['name' => 'Drama', 'slug' => 'drama'],
            ['name' => 'Comedy', 'slug' => 'comedy'],
            ['name' => 'Horror', 'slug' => 'horror'],
            ['name' => 'Animation', 'slug' => 'animation'],
            ['name' => 'Thriller', 'slug' => 'thriller'],
            ['name' => 'Adventure', 'slug' => 'adventure'],
        ];

        $genres = [];
        foreach ($genresData as $g) {
            $genres[$g['name']] = Genre::firstOrCreate(['slug' => $g['slug']], $g);
        }

        // 3. Create Sample Movies & TV Shows with high-quality poster images & metadata
        $mediaItems = [
            [
                'title' => 'Inception',
                'slug' => 'inception',
                'type' => 'movie',
                'description' => 'Seorang pencuri yang mencuri rahasia korporat melalui penggunaan teknologi berbagi mimpi diberikan tugas terbalik untuk menanamkan ide ke dalam pikiran seorang CEO.',
                'release_year' => 2010,
                'duration_or_seasons' => '2j 28m',
                'poster_url' => 'https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=600&auto=format&fit=crop&q=80',
                'banner_url' => 'https://images.unsplash.com/photo-1478760329108-5c3ed9d495a0?w=1200&auto=format&fit=crop&q=80',
                'trailer_url' => 'https://www.youtube.com/embed/YoHD9XEInc0',
                'director' => 'Christopher Nolan',
                'cast' => 'Leonardo DiCaprio, Joseph Gordon-Levitt, Elliot Page',
                'genres' => ['Action', 'Sci-Fi', 'Adventure'],
                'ratings' => [5, 5, 4, 5, 5],
                'comments' => [
                    ['user_name' => 'Rian_Pratama', 'comment_text' => 'Film mahakarya terbaik dari Nolan! Alur cerita luar biasa cerdas dan visual efek memukau.'],
                    ['user_name' => 'Siti_Nurbaya', 'comment_text' => 'Ending-nya bikin kepikiran berhari-hari. Sangat merekomendasikan film ini untuk pecinta Sci-Fi.'],
                ]
            ],
            [
                'title' => 'Stranger Things',
                'slug' => 'stranger-things',
                'type' => 'tv_show',
                'description' => 'Ketika seorang anak laki-laki menghilang, sebuah kota kecil mengungkap misteri yang melibatkan eksperimen rahasia, kekuatan supranatural yang menakutkan, dan seorang gadis kecil yang aneh.',
                'release_year' => 2016,
                'duration_or_seasons' => '4 Season',
                'poster_url' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=600&auto=format&fit=crop&q=80',
                'banner_url' => 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?w=1200&auto=format&fit=crop&q=80',
                'trailer_url' => 'https://www.youtube.com/embed/b9EkMc79ZSU',
                'director' => 'The Duffer Brothers',
                'cast' => 'Millie Bobby Brown, Finn Wolfhard, Winona Ryder',
                'genres' => ['Sci-Fi', 'Horror', 'Drama'],
                'ratings' => [5, 4, 5, 5],
                'comments' => [
                    ['user_name' => 'Budi_Santoso', 'comment_text' => 'Vibe retro 80s nya berasa banget! Akting Eleven keren parah.'],
                    ['user_name' => 'Dinda_Putri', 'comment_text' => 'Season 4 episode terbaik yang pernah ada di serial TV.'],
                ]
            ],
            [
                'title' => 'Interstellar',
                'slug' => 'interstellar',
                'type' => 'movie',
                'description' => 'Tim penjelajah menjelajahi lubang cacing di ruang angkasa dalam upaya untuk memastikan kelangsungan hidup umat manusia ketika bumi mulai kehabisan sumber daya.',
                'release_year' => 2014,
                'duration_or_seasons' => '2j 49m',
                'poster_url' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=600&auto=format&fit=crop&q=80',
                'banner_url' => 'https://images.unsplash.com/photo-1506703719100-a0f3a48c0f86?w=1200&auto=format&fit=crop&q=80',
                'trailer_url' => 'https://www.youtube.com/embed/zSWdZVtXT7E',
                'director' => 'Christopher Nolan',
                'cast' => 'Matthew McConaughey, Anne Hathaway, Jessica Chastain',
                'genres' => ['Sci-Fi', 'Drama', 'Adventure'],
                'ratings' => [5, 5, 5, 4, 5],
                'comments' => [
                    ['user_name' => 'FilmGeek99', 'comment_text' => 'Music scoring Hans Zimmer dipadu sains astronomi membuat film ini tidak pernah membosankan.'],
                ]
            ],
            [
                'title' => 'The Dark Knight',
                'slug' => 'the-dark-knight',
                'type' => 'movie',
                'description' => 'Ketika ancaman yang dikenal sebagai Joker merusak dan meretas kota Gotham, Batman harus menerima salah satu ujian psikologis dan fisik terbesar dari kemampuannya.',
                'release_year' => 2008,
                'duration_or_seasons' => '2j 32m',
                'poster_url' => 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?w=600&auto=format&fit=crop&q=80',
                'banner_url' => 'https://images.unsplash.com/photo-1534447677768-be436bb09401?w=1200&auto=format&fit=crop&q=80',
                'trailer_url' => 'https://www.youtube.com/embed/EXeTwQWrcwY',
                'director' => 'Christopher Nolan',
                'cast' => 'Christian Bale, Heath Ledger, Aaron Eckhart',
                'genres' => ['Action', 'Thriller', 'Drama'],
                'ratings' => [5, 5, 5, 5],
                'comments' => [
                    ['user_name' => 'Andi_Kurniawan', 'comment_text' => 'Akting Heath Ledger sebagai Joker adalah penampilan villian legendaris terbaik di industri perfilman.'],
                ]
            ],
            [
                'title' => 'Breaking Bad',
                'slug' => 'breaking-bad',
                'type' => 'tv_show',
                'description' => 'Seorang guru kimia sekolah menengah yang didiagnosis mengidap kanker paru-paru stadium akhir berubah menjadi pengusaha metamfetamina bersama mantan muridnya.',
                'release_year' => 2008,
                'duration_or_seasons' => '5 Season',
                'poster_url' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?w=600&auto=format&fit=crop&q=80',
                'banner_url' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=1200&auto=format&fit=crop&q=80',
                'trailer_url' => 'https://www.youtube.com/embed/HhesaQXLuRY',
                'director' => 'Vince Gilligan',
                'cast' => 'Bryan Cranston, Aaron Paul, Anna Gunn',
                'genres' => ['Drama', 'Thriller'],
                'ratings' => [5, 5, 5, 5, 5],
                'comments' => [
                    ['user_name' => 'CinematographyLover', 'comment_text' => 'Serial TV terbaik sepanjang sejarah. Pengembangan karakter Walter White luar biasa memukau.'],
                ]
            ],
            [
                'title' => 'Spider-Man: Across the Spider-Verse',
                'slug' => 'across-the-spider-verse',
                'type' => 'movie',
                'description' => 'Miles Morales terlempar melintasi Multiverse, di mana ia bertemu dengan tim Spider-People yang bertugas melindungi keberadaannya.',
                'release_year' => 2023,
                'duration_or_seasons' => '2j 20m',
                'poster_url' => 'https://images.unsplash.com/photo-1635805737707-575885ab0820?w=600&auto=format&fit=crop&q=80',
                'banner_url' => 'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?w=1200&auto=format&fit=crop&q=80',
                'trailer_url' => 'https://www.youtube.com/embed/cqGjhVJWtEg',
                'director' => 'Joaquim Dos Santos, Kemp Powers',
                'cast' => 'Shameik Moore, Hailee Steinfeld, Oscar Isaac',
                'genres' => ['Animation', 'Action', 'Adventure'],
                'ratings' => [5, 5, 4, 5],
                'comments' => [
                    ['user_name' => 'AnimasiKita', 'comment_text' => 'Animasi dan soundtrack-nya bikin merinding dari awal sampai akhir! Wajib nonton.'],
                ]
            ]
        ];

        foreach ($mediaItems as $item) {
            $itemGenres = $item['genres'];
            $ratings = $item['ratings'];
            $comments = $item['comments'];
            unset($item['genres'], $item['ratings'], $item['comments']);

            $media = Media::updateOrCreate(['slug' => $item['slug']], $item);

            // Attach Genres
            $genreIds = [];
            foreach ($itemGenres as $gName) {
                if (isset($genres[$gName])) {
                    $genreIds[] = $genres[$gName]->id;
                }
            }
            $media->genres()->sync($genreIds);

            // Add Ratings
            foreach ($ratings as $idx => $val) {
                Rating::create([
                    'media_id' => $media->id,
                    'user_identifier' => 'seed_guest_' . $media->id . '_' . $idx,
                    'rating' => $val,
                ]);
            }

            // Add Comments
            foreach ($comments as $c) {
                Comment::create([
                    'media_id' => $media->id,
                    'user_name' => $c['user_name'],
                    'comment_text' => $c['comment_text'],
                ]);
            }

            // Update computed rating average
            $media->updateAverageRating();
        }
    }
}

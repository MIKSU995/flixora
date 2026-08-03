<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';

    protected $fillable = [
        'title',
        'slug',
        'type',
        'description',
        'release_year',
        'duration_or_seasons',
        'poster_url',
        'banner_url',
        'trailer_url',
        'director',
        'cast',
        'avg_rating',
        'total_ratings'
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genre_media');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    /**
     * Recalculate average rating for this media item.
     */
    public function updateAverageRating()
    {
        $avg = $this->ratings()->avg('rating');
        $count = $this->ratings()->count();

        $this->avg_rating = round($avg ?? 0, 1);
        $this->total_ratings = $count;
        $this->save();
    }

    /**
     * Get genre-based recommendations (same genre, sorted by highest rating).
     */
    public function getGenreRecommendations($limit = 6)
    {
        $genreIds = $this->genres()->pluck('genres.id');

        if ($genreIds->isEmpty()) {
            return static::where('id', '!=', $this->id)
                ->orderBy('avg_rating', 'desc')
                ->take($limit)
                ->get();
        }

        return static::where('id', '!=', $this->id)
            ->whereHas('genres', function ($query) use ($genreIds) {
                $query->whereIn('genres.id', $genreIds);
            })
            ->orderBy('avg_rating', 'desc')
            ->orderBy('total_ratings', 'desc')
            ->take($limit)
            ->get();
    }
}

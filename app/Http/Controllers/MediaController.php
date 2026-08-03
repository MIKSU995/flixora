<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Rating;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    /**
     * Display media details, comments, and automatic genre recommendations.
     */
    public function show($slug, Request $request)
    {
        $media = Media::with(['genres', 'comments'])->where('slug', $slug)->firstOrFail();

        // Automatic Genre-based recommendations (Same Genre, highest rated)
        $recommendedMedia = $media->getGenreRecommendations(6);

        // Guest user identifier stored in cookie or session
        $userIdentifier = $request->cookie('guest_flixora_id');
        if (!$userIdentifier) {
            $userIdentifier = Str::uuid()->toString();
            cookie()->queue(cookie()->forever('guest_flixora_id', $userIdentifier));
        }

        // Existing rating given by this guest
        $userRating = Rating::where('media_id', $media->id)
            ->where('user_identifier', $userIdentifier)
            ->value('rating');

        return view('media.show', compact('media', 'recommendedMedia', 'userRating'));
    }

    /**
     * AJAX Submit Rating (Guest mode, no login required)
     */
    public function rate(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $media = Media::findOrFail($id);

        $userIdentifier = $request->cookie('guest_flixora_id') ?? $request->input('guest_id');
        if (!$userIdentifier) {
            $userIdentifier = Str::uuid()->toString();
            cookie()->queue(cookie()->forever('guest_flixora_id', $userIdentifier));
        }

        Rating::updateOrCreate(
            [
                'media_id' => $media->id,
                'user_identifier' => $userIdentifier,
            ],
            [
                'rating' => $request->rating,
            ]
        );

        // Recalculate average rating & count
        $media->updateAverageRating();

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih atas rating yang Anda berikan!',
            'avg_rating' => number_format($media->avg_rating, 1),
            'total_ratings' => $media->total_ratings,
            'user_rating' => (int) $request->rating,
        ]);
    }

    /**
     * AJAX Submit Comment (Guest mode with nickname)
     */
    public function comment(Request $request, $id)
    {
        $request->validate([
            'user_name' => 'required|string|max:50',
            'comment_text' => 'required|string|max:1000',
        ]);

        $media = Media::findOrFail($id);

        $comment = Comment::create([
            'media_id' => $media->id,
            'user_name' => trim($request->user_name),
            'comment_text' => trim($request->comment_text),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Komentar Anda berhasil dipublikasikan!',
            'comment' => [
                'id' => $comment->id,
                'user_name' => e($comment->user_name),
                'comment_text' => e($comment->comment_text),
                'created_at' => $comment->created_at->diffForHumans(),
            ]
        ]);
    }
}

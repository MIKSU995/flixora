<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\Genre;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MediaManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q');
        $type = $request->query('type');

        $query = Media::with('genres');

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($type) {
            $query->where('type', $type);
        }

        $mediaItems = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.media.index', compact('mediaItems', 'search', 'type'));
    }

    public function create()
    {
        $genres = Genre::orderBy('name', 'asc')->get();
        return view('admin.media.create', compact('genres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:movie,tv_show',
            'description' => 'required|string',
            'release_year' => 'required|integer|min:1900|max:' . (date('Y') + 5),
            'duration_or_seasons' => 'required|string|max:100',
            'poster_url' => 'required|string|max:1000',
            'banner_url' => 'nullable|string|max:1000',
            'trailer_url' => 'nullable|string|max:1000',
            'director' => 'nullable|string|max:255',
            'cast' => 'nullable|string',
            'genres' => 'required|array|min:1',
            'genres.*' => 'exists:genres,id',
        ]);

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $count = 1;
        while (Media::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        $media = Media::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'type' => $validated['type'],
            'description' => $validated['description'],
            'release_year' => $validated['release_year'],
            'duration_or_seasons' => $validated['duration_or_seasons'],
            'poster_url' => $validated['poster_url'],
            'banner_url' => $validated['banner_url'] ?? null,
            'trailer_url' => $validated['trailer_url'] ?? null,
            'director' => $validated['director'] ?? null,
            'cast' => $validated['cast'] ?? null,
        ]);

        $media->genres()->sync($validated['genres']);

        return redirect()->route('admin.media.index')
            ->with('success', 'Media "' . $media->title . '" berhasil ditambahkan ke katalog!');
    }

    public function edit($id)
    {
        $media = Media::with('genres')->findOrFail($id);
        $genres = Genre::orderBy('name', 'asc')->get();
        $selectedGenres = $media->genres->pluck('id')->toArray();

        return view('admin.media.edit', compact('media', 'genres', 'selectedGenres'));
    }

    public function update(Request $request, $id)
    {
        $media = Media::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:movie,tv_show',
            'description' => 'required|string',
            'release_year' => 'required|integer|min:1900|max:' . (date('Y') + 5),
            'duration_or_seasons' => 'required|string|max:100',
            'poster_url' => 'required|string|max:1000',
            'banner_url' => 'nullable|string|max:1000',
            'trailer_url' => 'nullable|string|max:1000',
            'director' => 'nullable|string|max:255',
            'cast' => 'nullable|string',
            'genres' => 'required|array|min:1',
            'genres.*' => 'exists:genres,id',
        ]);

        if ($media->title !== $validated['title']) {
            $slug = Str::slug($validated['title']);
            $originalSlug = $slug;
            $count = 1;
            while (Media::where('slug', $slug)->where('id', '!=', $media->id)->exists()) {
                $slug = "{$originalSlug}-{$count}";
                $count++;
            }
            $media->slug = $slug;
        }

        $media->update([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'description' => $validated['description'],
            'release_year' => $validated['release_year'],
            'duration_or_seasons' => $validated['duration_or_seasons'],
            'poster_url' => $validated['poster_url'],
            'banner_url' => $validated['banner_url'] ?? null,
            'trailer_url' => $validated['trailer_url'] ?? null,
            'director' => $validated['director'] ?? null,
            'cast' => $validated['cast'] ?? null,
        ]);

        $media->genres()->sync($validated['genres']);

        return redirect()->route('admin.media.index')
            ->with('success', 'Media "' . $media->title . '" berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $media = Media::findOrFail($id);
        $title = $media->title;
        $media->delete();

        return redirect()->route('admin.media.index')
            ->with('success', 'Media "' . $title . '" berhasil dihapus dari katalog.');
    }

    public function deleteComment($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $comment->delete();

        return back()->with('success', 'Komentar berhasil dihapus oleh Admin.');
    }
}

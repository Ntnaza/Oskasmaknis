<?php

namespace App\Http\Controllers;

use App\Models\Article; // Pastikan 'use' statement ini ada di atas
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function fetchAll()
    {
        return Article::orderBy('published_at', 'desc')->get();
    }

    /**
     * Mengambil 3 artikel terbaru untuk pratinjau di homepage.
     */
    public function fetchLatest()
    {
        // PERBAIKAN: Menggunakan model 'Article' yang sudah di-import di atas
        $latestArticles = Article::latest()->take(3)->get();

        return response()->json(['data' => $latestArticles]);
    }

    /**
     * * Menampilkan daftar semua artikel/galeri.
     */
    public function index()
    {
        // Ambil semua artikel, urutkan dari yang terbaru
        return Article::latest()->paginate(9);
    }

    /**
     * Menyimpan artikel/galeri baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'published_at' => 'nullable|date',
            'featured_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gallery_files.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('featured_image_file')) {
            $validated['featured_image_path'] = $request->file('featured_image_file')->store('articles', 'public');
        }

        $galleryPaths = [];
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                $path = $file->store('gallery', 'public');
                $galleryPaths[] = $path;
            }
        }
        $validated['gallery'] = $galleryPaths;

        $article = Article::create($validated);

        return response()->json($article, 201);
    }

    /**
     * Menampilkan detail satu artikel/galeri.
     */
    public function show(Article $article)
    {
        return $article;
    }

    /**
     * Memperbarui artikel/galeri yang ada.
     */
    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'published_at' => 'nullable|date',
            'featured_image_file' => 'nullable|image|max:2048',
            'gallery_files.*' => 'nullable|image|max:2048',
        ]);
        
        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('featured_image_file')) {
            if ($article->featured_image_path) {
                Storage::disk('public')->delete($article->featured_image_path);
            }
            $validated['featured_image_path'] = $request->file('featured_image_file')->store('articles', 'public');
        }

        $galleryPaths = $article->gallery ?? [];
        
        $imagesToDelete = json_decode($request->input('images_to_delete', '[]'), true);

        if (!empty($imagesToDelete)) {
            foreach ($imagesToDelete as $path) {
                Storage::disk('public')->delete($path);
            }
            $galleryPaths = array_diff($galleryPaths, $imagesToDelete);
        }

        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                $path = $file->store('gallery', 'public');
                $galleryPaths[] = $path;
            }
        }
        $validated['gallery'] = array_values($galleryPaths);

        $article->update($validated);

        return response()->json($article);
    }

    /**
     * Menghapus artikel/galeri.
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);

        if ($article->featured_image_path) {
            Storage::disk('public')->delete($article->featured_image_path);
        }
        if ($article->gallery) {
            foreach ($article->gallery as $path) {
                Storage::disk('public')->delete($path);
            }
        }
        
        $article->delete();
        return response()->json(['message' => 'Artikel/Galeri berhasil dihapus']);
    }   
}
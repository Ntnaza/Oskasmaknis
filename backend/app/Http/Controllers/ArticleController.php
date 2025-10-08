<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    /**
     * Menampilkan daftar semua artikel/galeri.
     */
    public function index()
    {
        // Ambil semua artikel, urutkan dari yang terbaru
        return Article::orderBy('published_at', 'desc')->get();
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
            'gallery_files.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi untuk multiple file
        ]);

        // Buat slug otomatis dari judul
        $validated['slug'] = Str::slug($validated['title']);

        // Handle upload gambar utama
        if ($request->hasFile('featured_image_file')) {
            $validated['featured_image_path'] = $request->file('featured_image_file')->store('articles', 'public');
        }

        // Handle upload multiple gambar galeri
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
    public function update(Request $request, Article $article)
    {
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
        if ($request->hasFile('gallery_files')) {
             // Di aplikasi nyata, Anda mungkin ingin logika untuk menghapus gambar lama,
             // tapi untuk sekarang kita hanya tambahkan yang baru.
            foreach ($request->file('gallery_files') as $file) {
                $path = $file->store('gallery', 'public');
                $galleryPaths[] = $path;
            }
        }
        $validated['gallery'] = $galleryPaths;

        $article->update($validated);

        return response()->json($article);
    }

    /**
     * Menghapus artikel/galeri.
     */
    public function destroy(Article $article)
    {
        // Hapus gambar utama
        if ($article->featured_image_path) {
            Storage::disk('public')->delete($article->featured_image_path);
        }
        // Hapus semua gambar di galeri
        if ($article->gallery) {
            foreach ($article->gallery as $path) {
                Storage::disk('public')->delete($path);
            }
        }
        
        $article->delete();
        return response()->json(['message' => 'Artikel/Galeri berhasil dihapus']);
    }
}

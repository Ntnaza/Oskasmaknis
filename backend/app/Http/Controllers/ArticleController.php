<?php

namespace App\Http\Controllers;

use App\Models\Article;
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
     * 
     * Menampilkan daftar semua artikel/galeri.
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
     * Fungsi ini tetap menggunakan Route Model Binding (berdasarkan slug) untuk halaman publik.
     */
    public function show(Article $article)
    {
        return $article;
    }

    /**
     * Memperbarui artikel/galeri yang ada.
     * PERUBAHAN DI SINI: Menggunakan $id dan mencari manual.
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

    // =======================================================
    // LOGIKA BARU UNTUK MENGHAPUS GAMBAR GALERI
    // =======================================================
    $galleryPaths = $article->gallery ?? [];
    
    // 1. Ambil daftar gambar yang akan dihapus dari request
    $imagesToDelete = json_decode($request->input('images_to_delete', '[]'), true);

    if (!empty($imagesToDelete)) {
        // Hapus file fisik dari storage
        foreach ($imagesToDelete as $path) {
            Storage::disk('public')->delete($path);
        }
        // Hapus path dari array galeri yang ada
        $galleryPaths = array_diff($galleryPaths, $imagesToDelete);
    }
    // =======================================================

    // Tambahkan file galeri baru yang di-upload
    if ($request->hasFile('gallery_files')) {
        foreach ($request->file('gallery_files') as $file) {
            $path = $file->store('gallery', 'public');
            $galleryPaths[] = $path;
        }
    }
    // Set array galeri final (setelah difilter dan ditambah)
    $validated['gallery'] = array_values($galleryPaths); // array_values untuk re-index array

    $article->update($validated);

    return response()->json($article);
}

    /**
     * Menghapus artikel/galeri.
     * PERUBAHAN DI SINI: Menggunakan $id dan mencari manual.
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id); // <-- Mencari manual berdasarkan ID

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
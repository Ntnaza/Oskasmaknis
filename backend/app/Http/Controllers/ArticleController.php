<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Angkatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    // --- API PUBLIC ---

    public function fetchAll(Request $request)
    {
        $query = Article::latest();

        if ($request->filled('angkatan_id')) {
            $query->where('angkatan_id', $request->input('angkatan_id'));
        } else {
            // Fallback ke angkatan aktif
            $active = Angkatan::where('is_active', true)->first();
            if ($active) $query->where('angkatan_id', $active->id);
        }

        return response()->json(['data' => $query->get()]);
    }

    public function fetchLatest(Request $request)
    {
        $query = Article::latest()->limit(3);

        if ($request->filled('angkatan_id')) {
            $query->where('angkatan_id', $request->input('angkatan_id'));
        } else {
            $active = Angkatan::where('is_active', true)->first();
            if ($active) $query->where('angkatan_id', $active->id);
        }
        
        return response()->json($query->get());
    }

    // --- API ADMIN ---

    public function index(Request $request)
    {
        $query = Article::latest();

        if ($request->filled('angkatan_id')) {
            $query->where('angkatan_id', $request->input('angkatan_id'));
        }

        return $query->paginate(9);
    }

    public function store(Request $request)
    {
        // 1. Validasi
        $validated = $request->validate([
            'angkatan_id' => 'required|exists:angkatans,id',
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'published_at' => 'nullable|date',
            'featured_image_file' => 'nullable|image|max:2048',
            'gallery_files.*' => 'nullable|image|max:2048', // Validasi array file
        ]);

        // 2. Generate Slug
        $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();

        // 3. Handle Featured Image
        if ($request->hasFile('featured_image_file')) {
            $validated['featured_image_path'] = $request->file('featured_image_file')->store('articles', 'public');
        }
        // Hapus key file object agar tidak error saat create
        unset($validated['featured_image_file']); 

        // 4. Handle Gallery (CRITICAL FIX)
        $galleryPaths = [];
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                // Simpan file dan ambil path-nya
                $galleryPaths[] = $file->store('gallery', 'public');
            }
        }
        
        // [PENTING] Timpa 'gallery_files' (yang isinya object) dengan array path string
        $validated['gallery_files'] = $galleryPaths; 

        // 5. Simpan
        $article = Article::create($validated);

        return response()->json($article, 201);
    }

    public function show(Article $article)
    {
        return $article;
    }

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
        
        if ($request->title !== $article->title) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();
        }

        // Handle Featured Image Update
        if ($request->hasFile('featured_image_file')) {
            if ($article->featured_image_path) {
                Storage::disk('public')->delete($article->featured_image_path);
            }
            $validated['featured_image_path'] = $request->file('featured_image_file')->store('articles', 'public');
        }
        unset($validated['featured_image_file']);

        // Handle Gallery Update
        // Ambil data lama dari database (gunakan nama kolom 'gallery_files')
        $galleryPaths = $article->gallery_files ?? [];
        
        // Hapus gambar yang dipilih
        $imagesToDelete = json_decode($request->input('images_to_delete', '[]'), true);
        if (!empty($imagesToDelete)) {
            foreach ($imagesToDelete as $path) {
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
                // Hapus path dari array
                $galleryPaths = array_values(array_diff($galleryPaths, [$path]));
            }
        }

        // Tambah gambar baru
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                $galleryPaths[] = $file->store('gallery', 'public');
            }
        }
        
        // Timpa data gallery_files dengan array path yang baru
        $validated['gallery_files'] = $galleryPaths;

        $article->update($validated);

        return response()->json($article);
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);

        if ($article->featured_image_path) {
            Storage::disk('public')->delete($article->featured_image_path);
        }
        
        // Pastikan nama propertinya 'gallery_files'
        if ($article->gallery_files) {
            foreach ($article->gallery_files as $path) {
                Storage::disk('public')->delete($path);
            }
        }
        
        $article->delete();
        return response()->json(['message' => 'Artikel/Galeri berhasil dihapus']);
    }   
}
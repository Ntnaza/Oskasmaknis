<?php

namespace App\Http\Controllers;

use App\Models\PageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageContentController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        // Cari konten di database, atau buat baru jika tidak ada
        $pageContent = PageContent::firstOrCreate(
            ['slug' => $slug],
            ['title' => 'Judul Default', 'subtitle' => 'Isi subtitel di sini.']
        );

        return response()->json($pageContent);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $slug)
    {
        $pageContent = PageContent::where('slug', $slug)->firstOrFail();

        // PERBAIKAN #1: Validasi yang lebih fleksibel
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string',
            'hero_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi file
        ]);

        // PERBAIKAN #2: Logika Update yang Benar
        // Update title dan subtitle terlebih dahulu
        $pageContent->update([
            'title' => $validatedData['title'],
            'subtitle' => $validatedData['subtitle'],
        ]);
        
        // Cek jika ada file gambar baru yang di-upload
        if ($request->hasFile('hero_image_file')) {
            // Hapus gambar lama jika ada untuk menghemat ruang
            if ($pageContent->hero_image_url) {
                Storage::disk('public')->delete($pageContent->hero_image_url);
            }

            // Simpan gambar baru dan dapatkan path-nya
            $path = $request->file('hero_image_file')->store('page-heroes', 'public');
            
            // Simpan path baru ke database
            $pageContent->hero_image_url = $path;
            $pageContent->save();
        }

        return response()->json([
            'message' => 'Konten berhasil diperbarui!',
            'data' => $pageContent->fresh(), // Kirim data terbaru setelah update
        ]);
    }
}


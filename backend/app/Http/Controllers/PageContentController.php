<?php

namespace App\Http\Controllers;

use App\Models\PageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // <-- Tambahkan import Storage
use Illuminate\Support\Facades\Log;     // <-- Tambahkan import Log

class PageContentController extends Controller
{
    /**
     * Menampilkan resource page content berdasarkan slug.
     */
    public function show(string $slug)
    {
        $pageContent = PageContent::where('slug', $slug)->firstOrFail();
        return response()->json(['data' => $pageContent]);
    }

    /**
     * Memperbarui resource page content berdasarkan slug.
     */
    public function update(Request $request, string $slug)
    {
        // 1. Cari data yang mau di-update.
        $pageContent = PageContent::where('slug', $slug)->firstOrFail();
        Log::info('Updating PageContent for slug: ' . $slug); // Log

        // 2. Validasi data teks (tanpa file di sini).
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
        ]);

        // 3. Update data teks di model.
        $pageContent->title = $validated['title'];
        $pageContent->subtitle = $validated['subtitle'];

        // ======================================================
        // ==     LOGIKA UPLOAD FILE 'hero_image_file'       ==
        // ======================================================
        if ($request->hasFile('hero_image_file')) {
            Log::info('File hero_image_file DITEMUKAN untuk slug: ' . $slug);

            // Hapus file lama jika ada path-nya tersimpan dan file-nya ada
            if ($pageContent->hero_image_url && Storage::disk('public')->exists($pageContent->hero_image_url)) {
                 Log::info('Menghapus hero_image_file lama: ' . $pageContent->hero_image_url);
                Storage::disk('public')->delete($pageContent->hero_image_url);
            }

            // Simpan file baru
            try {
                // Simpan di folder 'hero_images' (atau sesuaikan) di dalam storage/app/public
                $path = $request->file('hero_image_file')->store('hero_images', 'public');
                if ($path) {
                    Log::info('File hero_image_file BERHASIL disimpan. Path: ' . $path);
                    // Update path gambar di model
                    $pageContent->hero_image_url = $path;
                } else {
                    Log::error('Penyimpanan hero_image_file GAGAL (store return false/null).');
                    // Pertimbangkan untuk mengembalikan error jika penyimpanan gagal
                    // return response()->json(['message' => 'Gagal menyimpan gambar.'], 500);
                }
            } catch (\Exception $e) {
                Log::error('Error saat menyimpan hero_image_file: ' . $e->getMessage());
                // Kembalikan response error agar frontend tahu
                return response()->json(['message' => 'Terjadi error saat menyimpan gambar.'], 500);
            }
        } else {
             Log::warning('File hero_image_file TIDAK DITEMUKAN dalam request.');
        }
        // ======================================================

        // 4. Simpan semua perubahan ke database (termasuk path gambar jika ada).
        $pageContent->save();
        Log::info('PageContent untuk slug ' . $slug . ' berhasil disimpan.');

        // 5. Kembalikan pesan sukses beserta data terbaru (termasuk URL gambar baru).
        return response()->json([
            'message' => 'Page content updated successfully!',
            'data' => $pageContent // Kirim data terbaru
        ]);
    }
}
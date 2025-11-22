<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Angkatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // <-- TAMBAHKAN INI

class AngkatanController extends Controller
{
    /**
     * Menampilkan daftar semua angkatan.
     */
    public function index()
    {
        return Angkatan::orderBy('year_period', 'desc')->get();
    }

    /**
     * Menyimpan angkatan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'year_period' => 'required|string|max:20',
            'theme_color' => 'required|string|max:7', 
            'is_active' => 'boolean',
            // Validasi gambar background
            'card_background_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Jika yang baru diset aktif, matikan yang lama
        if ($request->is_active) {
            Angkatan::where('is_active', true)->update(['is_active' => false]);
        }

        // --- LOGIKA UPLOAD BACKGROUND ---
        if ($request->hasFile('card_background_file')) {
            $path = $request->file('card_background_file')->store('angkatan_backgrounds', 'public');
            $validated['card_background_path'] = $path;
        }
        // --------------------------------

        $angkatan = Angkatan::create($validated);

        return response()->json($angkatan, 201);
    }

    /**
     * Menampilkan detail satu angkatan.
     */
    public function show(Angkatan $angkatan)
    {
        return $angkatan;
    }

    /**
     * Update angkatan.
     */
    public function update(Request $request, Angkatan $angkatan)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'year_period' => 'sometimes|required|string|max:20',
            'theme_color' => 'sometimes|required|string|max:7',
            'is_active' => 'boolean',
            'card_background_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Jika diupdate jadi aktif, matikan yang lain
        if (isset($validated['is_active']) && $validated['is_active']) {
            Angkatan::where('id', '!=', $angkatan->id)->update(['is_active' => false]);
        }

        // --- LOGIKA UPDATE BACKGROUND ---
        if ($request->hasFile('card_background_file')) {
            // 1. Hapus file lama jika ada
            if ($angkatan->card_background_path) {
                Storage::disk('public')->delete($angkatan->card_background_path);
            }
            // 2. Upload file baru
            $path = $request->file('card_background_file')->store('angkatan_backgrounds', 'public');
            $validated['card_background_path'] = $path;
        }
        // --------------------------------

        $angkatan->update($validated);

        return response()->json($angkatan);
    }

    /**
     * Hapus angkatan.
     */
    public function destroy(Angkatan $angkatan)
    {
        // Hapus background jika ada
        if ($angkatan->card_background_path) {
            Storage::disk('public')->delete($angkatan->card_background_path);
        }

        $angkatan->delete();
        return response()->json(null, 204);
    }
}
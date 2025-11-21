<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Angkatan;
use Illuminate\Http\Request;

class AngkatanController extends Controller
{
    /**
     * Menampilkan daftar semua angkatan.
     * (Dipanggil oleh Pinia Store: fetchAngkatans)
     */
    public function index()
    {
        // Urutkan dari tahun terbaru ke terlama
        return Angkatan::orderBy('year_period', 'desc')->get();
    }

    /**
     * Menyimpan angkatan baru (Untuk Admin nanti).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'year_period' => 'required|string|max:20',
            'theme_color' => 'required|string|max:7', // Format Hex #RRGGBB
            'is_active' => 'boolean',
        ]);

        // Jika yang baru diset aktif, matikan yang lama
        if ($request->is_active) {
            Angkatan::where('is_active', true)->update(['is_active' => false]);
        }

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
     * Update angkatan (Untuk Admin nanti).
     */
    public function update(Request $request, Angkatan $angkatan)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'year_period' => 'sometimes|required|string|max:20',
            'theme_color' => 'sometimes|required|string|max:7',
            'is_active' => 'boolean',
        ]);

        // Jika diupdate jadi aktif, matikan yang lain
        if (isset($validated['is_active']) && $validated['is_active']) {
            Angkatan::where('id', '!=', $angkatan->id)->update(['is_active' => false]);
        }

        $angkatan->update($validated);

        return response()->json($angkatan);
    }

    /**
     * Hapus angkatan.
     */
    public function destroy(Angkatan $angkatan)
    {
        $angkatan->delete();
        return response()->json(null, 204);
    }
}
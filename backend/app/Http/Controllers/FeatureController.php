<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\Angkatan; // <-- 1. Import Model Angkatan untuk fallback
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    // Mengambil fitur (difilter per angkatan)
    public function index(Request $request)
    {
        $query = Feature::orderBy('order', 'asc');

        // --- LOGIKA FILTER ANGKATAN ---
        if ($request->filled('angkatan_id')) {
            // Jika frontend mengirim ID angkatan (dari Sidebar Admin / Dropdown)
            $query->where('angkatan_id', $request->angkatan_id);
        } else {
            // Fallback: Jika tidak dikirim (misal pengunjung umum), ambil Angkatan Aktif
            $activeAngkatan = Angkatan::where('is_active', true)->first();
            if ($activeAngkatan) {
                $query->where('angkatan_id', $activeAngkatan->id);
            }
        }
        // ------------------------------

        return $query->get();
    }

    // Menyimpan fitur baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'angkatan_id' => 'required|exists:angkatans,id', // <-- WAJIB ADA
            'icon' => 'required|string',
            'color' => 'required|string',
            'title' => 'required|string',
            'description' => 'required|string',
            'order' => 'required|integer',
        ]);

        $feature = Feature::create($validated);
        return response()->json($feature, 201);
    }

    // Mengambil satu fitur spesifik
    public function show(Feature $feature)
    {
        return $feature;
    }

    // Memperbarui fitur yang ada
    public function update(Request $request, Feature $feature)
    {
        $validated = $request->validate([
            // 'angkatan_id' biasanya tidak berubah saat edit, tapi boleh divalidasi
            'angkatan_id' => 'sometimes|exists:angkatans,id', 
            
            'icon' => 'required|string',
            'color' => 'required|string',
            'title' => 'required|string',
            'description' => 'required|string',
            'order' => 'required|integer',
        ]);

        $feature->update($validated);
        return response()->json($feature);
    }

    // Menghapus fitur
    public function destroy(Feature $feature)
    {
        $feature->delete();
        return response()->json(['message' => 'Fitur berhasil dihapus']);
    }
}
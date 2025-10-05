<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    // Mengambil semua fitur, diurutkan berdasarkan 'order'
    public function index()
    {
        return Feature::orderBy('order', 'asc')->get();
    }

    // Menyimpan fitur baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'icon' => 'required|string',
            'color' => 'required|string', // <-- TAMBAHKAN INI
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
            'icon' => 'required|string',
            'color' => 'required|string', // <-- TAMBAHKAN INI
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
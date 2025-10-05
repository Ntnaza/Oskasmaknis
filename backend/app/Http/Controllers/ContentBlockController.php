<?php

namespace App\Http\Controllers;

use App\Models\ContentBlock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;

class ContentBlockController extends Controller
{
    /**
     * Menampilkan data content block berdasarkan section_key.
     */
    public function show(string $key)
    {
        $block = ContentBlock::firstOrCreate(['section_key' => $key]);
        return response()->json($block);
    }

    /**
     * Memperbarui content block.
     */
    public function update(Request $request, string $key)
    {
        $block = ContentBlock::where('section_key', $key)->firstOrFail();

        // --- PERBAIKAN UTAMA DIMULAI DI SINI ---
        
        // 1. "Buka bungkusan" JSON string dari 'content' menjadi array
        $contentData = json_decode($request->input('content'), true);

        // 2. Gabungkan data yang sudah dibuka dengan sisa request
        //    Ini agar validator bisa melihat 'content.title', 'content.description', dll.
        $request->merge(['content' => $contentData]);

        // --- AKHIR PERBAIKAN ---

        $validated = $request->validate([
            'content.title' => 'required|string',
            'content.description' => 'required|string',
            'content.icon' => 'required|string',
            'content.list_items' => 'sometimes|array',
            'content.list_items.*.icon' => 'required_with:content.list_items|string',
            'content.list_items.*.text' => 'required_with:content.list_items|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        // Ambil kembali data konten yang sudah divalidasi
        $finalContentData = $validated['content'];

        // Cek jika ada file gambar baru yang di-upload
        if ($request->hasFile('image_file')) {
            $currentContent = $block->content;
            if (isset($currentContent['image_url'])) {
                Storage::disk('public')->delete($currentContent['image_url']);
            }
            $path = $request->file('image_file')->store('promo-images', 'public');
            $finalContentData['image_url'] = $path;
        } else {
             // Jika tidak ada gambar baru, pastikan URL gambar lama tidak terhapus
             $finalContentData['image_url'] = $block->content['image_url'] ?? null;
        }

        // Update seluruh kolom 'content' dengan data baru
        $block->update(['content' => $finalContentData]);

        return response()->json([
            'message' => 'Blok konten berhasil diperbarui!',
            'data' => $block->fresh(),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ContentBlock;
use App\Models\Angkatan; // <-- 1. Import Angkatan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ContentBlockController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['page_slug' => 'required|string']);
        
        // --- FILTER ANGKATAN ---
        $query = ContentBlock::where('page_slug', $request->page_slug);
        
        if ($request->filled('angkatan_id')) {
            $query->where('angkatan_id', $request->input('angkatan_id'));
        } else {
             // Fallback ke aktif
            $activeAngkatan = Angkatan::where('is_active', true)->first();
            if($activeAngkatan) {
                $query->where('angkatan_id', $activeAngkatan->id);
            }
        }
        // -----------------------

        $blocks = $query->get();
        return response()->json(['data' => $blocks]);
    }

    public function show($key, Request $request)
    {
        Log::info('Mencari ContentBlock dengan section_key: ' . $key);
        
        // --- 2. LOGIKA ANGKATAN ---
        $angkatanId = $request->query('angkatan_id');

        // Jika tidak ada, ambil yang aktif
        if (!$angkatanId) {
            $activeAngkatan = Angkatan::where('is_active', true)->first();
            $angkatanId = $activeAngkatan ? $activeAngkatan->id : null;
        }

        if (!$angkatanId) {
            return response()->json(['message' => 'Tidak ada angkatan aktif.'], 404);
        }

        // Cari block spesifik untuk angkatan ini
        $block = ContentBlock::where('section_key', $key)
                             ->where('angkatan_id', $angkatanId)
                             ->first();

        // Jika tidak ada, return struktur kosong (jangan fail) agar frontend bisa isi
        if (!$block) {
             Log::info('ContentBlock belum ada untuk angkatan ini, mengembalikan template kosong.');
             return response()->json(['data' => [
                 'section_key' => $key,
                 'content' => [],
                 'angkatan_id' => $angkatanId
             ]]);
        }
        // ---------------------------

        Log::info('ContentBlock ditemukan:', ['id' => $block->id]);
        return response()->json(['data' => $block]);
    }

    public function update(Request $request, string $key)
    {
        Log::info('Memulai proses update untuk key: ' . $key);

        // --- 3. LOGIKA ANGKATAN (CREATE OR UPDATE) ---
        $angkatanId = $request->input('angkatan_id');
        
        // Fallback wajib
        if (!$angkatanId) {
            $activeAngkatan = Angkatan::where('is_active', true)->first();
            $angkatanId = $activeAngkatan->id;
        }

        // Cari atau Buat Baru
        $block = ContentBlock::firstOrNew([
            'section_key' => $key,
            'angkatan_id' => $angkatanId
        ]);
        
        // Isi data wajib jika baru
        if (!$block->exists) {
             // Kita perlu tahu page_slug. 
             // IDEALNYA frontend mengirim page_slug. 
             // TAPI untuk kompatibilitas, kita bisa ambil dari request atau default 'index'
             $block->page_slug = $request->input('page_slug', 'index'); 
        }
        // ---------------------------------------------

        $contentData = json_decode($request->input('content'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Gagal decode JSON content:', ['json_input' => $request->input('content')]);
            return response()->json(['message' => 'Invalid content data format.'], 400);
        }
        Log::info('Data content berhasil di-decode.');

        // Merge dengan konten lama (agar data lain tidak hilang)
        $currentContent = $block->content ?? [];
        $newContent = array_merge($currentContent, $contentData);

        if ($request->hasFile('image_file')) {
            Log::info('File image_file DITEMUKAN untuk key: ' . $key);

            if (isset($block->content['image_url']) && $block->content['image_url'] && Storage::disk('public')->exists($block->content['image_url'])) {
                 Log::info('Menghapus image_file lama: ' . $block->content['image_url']);
                Storage::disk('public')->delete($block->content['image_url']);
            }

            try {
                $path = $request->file('image_file')->store('promo_images', 'public');
                if ($path) {
                    Log::info('File image_file BERHASIL disimpan. Path: ' . $path);
                    $newContent['image_url'] = $path;
                } else {
                    Log::error('Penyimpanan image_file GAGAL (store return false/null).');
                }
            } catch (\Exception $e) {
                Log::error('Error saat menyimpan image_file: ' . $e->getMessage());
                return response()->json(['message' => 'Terjadi error saat menyimpan gambar.'], 500);
            }
        } else {
             // Jangan hapus URL lama jika tidak ada file baru
        }

        $block->content = $newContent;
        $block->save();
        Log::info('ContentBlock untuk key ' . $key . ' berhasil disimpan (Angkatan ID: ' . $angkatanId . ').');

        return response()->json([
            'message' => 'Content block updated successfully!',
            'data' => $block
        ]);
    }

    // --- METHOD BULK JUGA PERLU DISESUAIKAN ---
    public function updateBulk(Request $request)
    {
        Log::info('Memulai proses updateBulk.');

        $blocksData = json_decode($request->input('blocks'), true);
        
        // Ambil angkatan_id dari request (WAJIB DIKIRIM FRONTEND)
        $angkatanId = $request->input('angkatan_id');
        if (!$angkatanId) {
             $active = Angkatan::where('is_active', true)->first();
             $angkatanId = $active ? $active->id : null;
        }

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Gagal decode JSON blocks');
            return response()->json(['message' => 'Invalid block data format.'], 400);
        }

        foreach ($blocksData as $blockData) {
            // Logika: Cari berdasarkan KEY dan ANGKATAN, bukan ID
            // Karena ID mungkin belum ada untuk angkatan baru
            
            $key = $blockData['section_key'] ?? null;
            if (!$key) continue;

            $block = ContentBlock::firstOrNew([
                'section_key' => $key,
                'angkatan_id' => $angkatanId
            ]);
            
            if (!$block->exists) {
                $block->page_slug = 'index'; // Default/Asumsi
            }

            $currentContent = $block->content ?? [];
            $newContent = array_merge($currentContent, $blockData['content']);

            // --- LOGIKA FILE UPLOAD (OSIS/MPK/PEMBINA) ---
            // (Saya salin ulang logika Anda yang sudah ada)
            
            if ($key === 'index-sambutan-ketua') {
                if ($request->hasFile('osisFile')) {
                    if (isset($block->content['ketua_osis_image_path']) && $block->content['ketua_osis_image_path']) {
                        Storage::disk('public')->delete($block->content['ketua_osis_image_path']);
                    }
                    $newContent['ketua_osis_image_path'] = $request->file('osisFile')->store('leaders', 'public');
                }
                if ($request->hasFile('mpkFile')) {
                    if (isset($block->content['ketua_mpk_image_path']) && $block->content['ketua_mpk_image_path']) {
                        Storage::disk('public')->delete($block->content['ketua_mpk_image_path']);
                    }
                    $newContent['ketua_mpk_image_path'] = $request->file('mpkFile')->store('leaders', 'public');
                }
            }
            elseif ($key === 'index-pembina') {
                if ($request->hasFile('pembinaFile')) {
                    if (isset($block->content['pembina_image_path']) && $block->content['pembina_image_path']) {
                        Storage::disk('public')->delete($block->content['pembina_image_path']);
                    }
                    $newContent['pembina_image_path'] = $request->file('pembinaFile')->store('pembina', 'public');
                }
            }
            // ---------------------------------------------

            $block->content = $newContent;
            $block->save();
        }

        Log::info('Proses updateBulk selesai.');
        return response()->json(['message' => 'Content blocks updated successfully']);
    }
}
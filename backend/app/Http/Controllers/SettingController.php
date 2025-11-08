<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Mengambil path background yang tersimpan
     */
    public function getBackground()
    {
        $setting = Setting::where('key', 'card_background')->first();

        if ($setting) {
            // Kita kirim path yang bisa diakses publik
            return response()->json([
                'value' => Storage::url($setting->value)
            ]);
        }

        return response()->json(['value' => null]);
    }

    /**
     * Meng-upload dan menyimpan background baru
     */
    public function updateBackground(Request $request)
    {
        $request->validate([
            'background' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Cari setting yang lama
        $setting = Setting::firstOrNew(['key' => 'card_background']);

        // Jika ada gambar lama, hapus dari storage
        if ($setting->value) {
            Storage::disk('public')->delete($setting->value);
        }

        // Simpan gambar baru di 'storage/app/public/backgrounds'
        // 'public' adalah nama disk
        $path = $request->file('background')->store('backgrounds', 'public');

        // Simpan path baru di database (e.g., 'backgrounds/namafile.jpg')
        $setting->value = $path;
        $setting->save();

        return response()->json([
            'message' => 'Background berhasil disimpan!',
            'path' => Storage::url($path) // Kirim kembali URL publiknya
        ]);
    }
}
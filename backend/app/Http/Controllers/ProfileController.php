<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Menampilkan data profil organisasi.
     * Selalu mengambil data dengan id = 1.
     */
    public function show()
    {
        // Cari profil dengan id=1, atau buat baru jika tidak ada
        $profile = Profile::firstOrCreate(['id' => 1]);
        return response()->json($profile);
    }

    /**
     * Memperbarui data profil organisasi.
     */
    public function update(Request $request)
    {
        $profile = Profile::findOrFail(1);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'about' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'header_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'location' => $validated['location'],
            'about' => $validated['about'],
        ];

        // Cek dan proses upload gambar profil baru
        if ($request->hasFile('profile_image')) {
            if ($profile->profile_image_path) {
                Storage::disk('public')->delete($profile->profile_image_path);
            }
            $path = $request->file('profile_image')->store('profiles', 'public');
            $updateData['profile_image_path'] = $path;
        }

        // Cek dan proses upload gambar header baru
        if ($request->hasFile('header_image')) {
            if ($profile->header_image_path) {
                Storage::disk('public')->delete($profile->header_image_path);
            }
            $path = $request->file('header_image')->store('profiles', 'public');
            $updateData['header_image_path'] = $path;
        }
        
        $profile->update($updateData);

        return response()->json([
            'message' => 'Profil berhasil diperbarui!',
            'data' => $profile->fresh(),
        ]);
    }
}

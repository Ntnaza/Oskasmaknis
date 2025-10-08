<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TeamMemberController extends Controller
{
    /**
     * Menampilkan daftar semua anggota tim.
     */
    public function index()
    {
        return TeamMember::orderBy('order', 'asc')->get();
    }

    /**
     * Menyimpan anggota tim baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'order' => 'required|integer',
            'photo_file' => 'nullable|image|max:2048',
            'header_photo_file' => 'nullable|image|max:2048',
            
            // PENAMBAHAN: Validasi yang lebih spesifik untuk social_links
            'social_links' => 'nullable|array',
            'social_links.instagram' => 'nullable|url',
            'social_links.facebook' => 'nullable|url',
            'social_links.email' => 'nullable|email',

            // PENAMBAHAN: Validasi yang lebih spesifik untuk bio_data
            'bio_data' => 'nullable|array',
            'bio_data.sambutan' => 'nullable|string',
            'bio_data.jurusan' => 'nullable|string',
            'bio_data.ttl' => 'nullable|string',
            'bio_data.hobi' => 'nullable|string',
            'bio_data.visi_misi' => 'nullable|string',
            'bio_data.prestasi' => 'nullable|array',
            'bio_data.prestasi.*' => 'nullable|string', // Memastikan semua item prestasi adalah string
        ]);

        if ($request->hasFile('photo_file')) {
            $validated['photo_path'] = $request->file('photo_file')->store('team-photos', 'public');
        }
        if ($request->hasFile('header_photo_file')) {
            $validated['header_photo_path'] = $request->file('header_photo_file')->store('team-headers', 'public');
        }

        $teamMember = TeamMember::create($validated);

        return response()->json($teamMember, 201);
    }

    /**
     * Menampilkan detail satu anggota tim beserta program kerjanya.
     */
    public function show(TeamMember $teamMember)
    {
        // Fungsi ini sudah benar, memuat relasi program kerja.
        // Nanti bisa ditambahkan relasi galeri jika sudah dibuat.
        return $teamMember->load('workPrograms');
    }

    /**
     * Memperbarui anggota tim yang ada.
     */
    public function update(Request $request, TeamMember $teamMember)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'order' => 'required|integer',
            'photo_file' => 'nullable|image|max:2048',
            'header_photo_file' => 'nullable|image|max:2048',

            // PENAMBAHAN: Validasi yang lebih spesifik untuk social_links
            'social_links' => 'nullable|array',
            'social_links.instagram' => 'nullable|url',
            'social_links.facebook' => 'nullable|url',
            'social_links.email' => 'nullable|email',

            // PENAMBAHAN: Validasi yang lebih spesifik untuk bio_data
            'bio_data' => 'nullable|array',
            'bio_data.sambutan' => 'nullable|string',
            'bio_data.jurusan' => 'nullable|string',
            'bio_data.ttl' => 'nullable|string',
            'bio_data.hobi' => 'nullable|string',
            'bio_data.visi_misi' => 'nullable|string',
            'bio_data.prestasi' => 'nullable|array',
            'bio_data.prestasi.*' => 'nullable|string',
        ]);

        if ($request->hasFile('photo_file')) {
            if ($teamMember->photo_path) {
                Storage::disk('public')->delete($teamMember->photo_path);
            }
            $validated['photo_path'] = $request->file('photo_file')->store('team-photos', 'public');
        }
        
        if ($request->hasFile('header_photo_file')) {
            if ($teamMember->header_photo_path) {
                Storage::disk('public')->delete($teamMember->header_photo_path);
            }
            $validated['header_photo_path'] = $request->file('header_photo_file')->store('team-headers', 'public');
        }

        $teamMember->update($validated);

        return response()->json($teamMember);
    }

    /**
     * Menghapus anggota tim.
     */
    public function destroy(TeamMember $teamMember)
    {
        if ($teamMember->photo_path) {
            Storage::disk('public')->delete($teamMember->photo_path);
        }
        if ($teamMember->header_photo_path) {
            Storage::disk('public')->delete($teamMember->header_photo_path);
        }
        $teamMember->delete();
        return response()->json(['message' => 'Anggota tim berhasil dihapus']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamMemberController extends Controller
{
    /**
     * Menampilkan daftar semua anggota tim.
     */
    public function index()
    {
        // Ambil semua anggota tim, diurutkan berdasarkan kolom 'order'
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
            'social_links' => 'nullable|array',
            'photo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('photo_file')) {
            $validated['photo_path'] = $request->file('photo_file')->store('team-photos', 'public');
        }

        $teamMember = TeamMember::create($validated);

        return response()->json($teamMember, 201);
    }

    /**
     * Menampilkan detail satu anggota tim beserta program kerjanya.
     */
    public function show(TeamMember $teamMember)
    {
        // Muat data anggota tim BERSAMA DENGAN relasi 'workPrograms'
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
            'social_links' => 'nullable|array',
            'photo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('photo_file')) {
            // Hapus foto lama jika ada
            if ($teamMember->photo_path) {
                Storage::disk('public')->delete($teamMember->photo_path);
            }
            $validated['photo_path'] = $request->file('photo_file')->store('team-photos', 'public');
        }

        $teamMember->update($validated);

        return response()->json($teamMember);
    }

    /**
     * Menghapus anggota tim.
     */
    public function destroy(TeamMember $teamMember)
    {
        // Hapus foto dari storage saat anggota dihapus
        if ($teamMember->photo_path) {
            Storage::disk('public')->delete($teamMember->photo_path);
        }
        $teamMember->delete();
        return response()->json(['message' => 'Anggota tim berhasil dihapus']);
    }
}


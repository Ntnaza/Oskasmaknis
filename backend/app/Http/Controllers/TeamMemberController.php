<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // <-- Tambahkan ini
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException; // <-- Tambahkan ini

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
        Log::info('Store request data (raw):', $request->all()); // Debug

        // Decode JSON string jika ada
        $socialLinksData = $request->input('social_links') && is_string($request->input('social_links'))
            ? json_decode($request->input('social_links'), true)
            : ($request->input('social_links') ?? []); // Default array kosong jika null/sudah array
        $bioData = $request->input('bio_data') && is_string($request->input('bio_data'))
            ? json_decode($request->input('bio_data'), true)
            : ($request->input('bio_data') ?? []); // Default array kosong

        // Handle JSON decode error
        if ($request->input('social_links') && is_string($request->input('social_links')) && json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['message' => 'Format social_links JSON tidak valid.'], 400);
        }
        if ($request->input('bio_data') && is_string($request->input('bio_data')) && json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['message' => 'Format bio_data JSON tidak valid.'], 400);
        }

        // Merge data yang sudah di-decode ke request untuk validasi
        $request->merge([
            'social_links' => is_array($socialLinksData) ? $socialLinksData : [], // Pastikan array
            'bio_data' => is_array($bioData) ? $bioData : [], // Pastikan array
        ]);

        Log::info('Store request data (after merge):', $request->all()); // Debug

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'position' => 'required|string|max:255',
                'order' => 'required|integer|min:0',
                'photo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Max 2MB
                'header_photo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Max 5MB

                // Validasi social_links sebagai array (setelah decode)
                'social_links' => 'nullable|array',
                'social_links.*.platform' => 'sometimes|required|string', // Validasi tiap item dalam array
                'social_links.*.url' => 'sometimes|required|string',    // Pertimbangkan validasi 'url' jika perlu format URL ketat

                // Validasi bio_data sebagai array (setelah decode)
                'bio_data' => 'nullable|array',
                'bio_data.jurusan' => 'nullable|string|max:255',
                'bio_data.ttl' => 'nullable|string|max:255',
                'bio_data.hobi' => 'nullable|string|max:255',
                'bio_data.sambutan' => 'nullable|string',
                'bio_data.visi_misi' => 'nullable|string',
                'bio_data.prestasi' => 'nullable|array',
                'bio_data.prestasi.*' => 'nullable|string',
            ]);

            Log::info('Store validated data:', $validated); // Debug

            if ($request->hasFile('photo_file')) {
                $validated['photo_path'] = $request->file('photo_file')->store('team_photos', 'public');
            }
            if ($request->hasFile('header_photo_file')) {
                $validated['header_photo_path'] = $request->file('header_photo_file')->store('team_headers', 'public');
            }

            // Hapus key file dari $validated agar tidak mencoba disimpan ke DB
            unset($validated['photo_file']);
            unset($validated['header_photo_file']);

            $teamMember = TeamMember::create($validated);

            return response()->json(['message' => 'Anggota tim baru berhasil ditambahkan!', 'data' => $teamMember], 201);

        } catch (ValidationException $e) {
            Log::error('Store validation failed:', $e->errors());
            return response()->json([
                'message' => 'Data yang diberikan tidak valid.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error storing team member:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data.'], 500);
        }
    }

    /**
     * Menampilkan detail satu anggota tim beserta program kerjanya.
     */
    public function show(TeamMember $teamMember)
    {
        // Fungsi ini sudah benar
        return $teamMember->load('workPrograms');
    }

    /**
     * Memperbarui anggota tim yang ada.
     */
    public function update(Request $request, TeamMember $teamMember)
    {
        Log::info('Update request data (raw):', $request->all()); // Debug

        // Decode JSON string jika ada
        $socialLinksData = $request->input('social_links') && is_string($request->input('social_links'))
            ? json_decode($request->input('social_links'), true)
            : ($request->input('social_links') ?? []);
        $bioData = $request->input('bio_data') && is_string($request->input('bio_data'))
            ? json_decode($request->input('bio_data'), true)
            : ($request->input('bio_data') ?? []);

        // Handle JSON decode error
        if ($request->input('social_links') && is_string($request->input('social_links')) && json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['message' => 'Format social_links JSON tidak valid.'], 400);
        }
        if ($request->input('bio_data') && is_string($request->input('bio_data')) && json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['message' => 'Format bio_data JSON tidak valid.'], 400);
        }

        // Merge data yang sudah di-decode ke request untuk validasi
        $request->merge([
            'social_links' => is_array($socialLinksData) ? $socialLinksData : [],
            'bio_data' => is_array($bioData) ? $bioData : [],
        ]);

         Log::info('Update request data (after merge):', $request->all()); // Debug

        try {
             $validated = $request->validate([
                'name' => 'required|string|max:255',
                'position' => 'required|string|max:255',
                'order' => 'required|integer|min:0',
                'photo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'header_photo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',

                // Validasi social_links sebagai array (setelah decode)
                'social_links' => 'nullable|array',
                'social_links.*.platform' => 'sometimes|required|string',
                'social_links.*.url' => 'sometimes|required|string',

                // Validasi bio_data sebagai array (setelah decode)
                'bio_data' => 'nullable|array',
                'bio_data.jurusan' => 'nullable|string|max:255',
                'bio_data.ttl' => 'nullable|string|max:255',
                'bio_data.hobi' => 'nullable|string|max:255',
                'bio_data.sambutan' => 'nullable|string',
                'bio_data.visi_misi' => 'nullable|string',
                'bio_data.prestasi' => 'nullable|array',
                'bio_data.prestasi.*' => 'nullable|string',
            ]);

            Log::info('Update validated data:', $validated); // Debug

            // Handle file update (hapus yang lama jika ada baru)
            if ($request->hasFile('photo_file')) {
                if ($teamMember->photo_path) {
                    Storage::disk('public')->delete($teamMember->photo_path);
                }
                $validated['photo_path'] = $request->file('photo_file')->store('team_photos', 'public');
            }

            if ($request->hasFile('header_photo_file')) {
                if ($teamMember->header_photo_path) {
                    Storage::disk('public')->delete($teamMember->header_photo_path);
                }
                $validated['header_photo_path'] = $request->file('header_photo_file')->store('team_headers', 'public');
            }

            // Hapus key file dari $validated
            unset($validated['photo_file']);
            unset($validated['header_photo_file']);

            $teamMember->update($validated);

            // Ambil data terbaru setelah update
            $updatedMember = $teamMember->fresh();
            Log::info('Updated team member data:', $updatedMember->toArray()); // Debug

            return response()->json(['message' => 'Anggota tim berhasil diperbarui!', 'data' => $updatedMember]);

        } catch (ValidationException $e) {
            Log::error('Update validation failed:', $e->errors());
            return response()->json([
                'message' => 'Data yang diberikan tidak valid.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating team member:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data.'], 500);
        }
    }

    /**
     * Menghapus anggota tim.
     */
    public function destroy(TeamMember $teamMember)
    {
        try {
            if ($teamMember->photo_path) {
                Storage::disk('public')->delete($teamMember->photo_path);
            }
            if ($teamMember->header_photo_path) {
                Storage::disk('public')->delete($teamMember->header_photo_path);
            }
            $teamMember->delete();
            return response()->json(['message' => 'Anggota tim berhasil dihapus']);
        } catch (\Exception $e) {
            Log::error('Error deleting team member:', ['message' => $e->getMessage()]);
             return response()->json(['message' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
    }
}
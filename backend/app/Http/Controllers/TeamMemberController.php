<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str; 
use SimpleSoftwareIO\QrCode\Facades\QrCode; 

class TeamMemberController extends Controller
{
    /**
     * Menampilkan daftar anggota tim dengan Logika "Smart Filtering".
     */
    public function index(Request $request)
    {
        // 1. Eager Loading ('with'): Mengambil data angkatan sekaligus dalam 1 query.
        //    Ini mencegah masalah N+1 Query (Loading lambat saat data banyak).
        $query = TeamMember::with('angkatan')->orderBy('order', 'asc');

        // --- LOGIKA PENALARAN TINGGI (SMART FILTER) ---
        
        // Skenario A: Frontend meminta angkatan spesifik (User klik dropdown)
        if ($request->filled('angkatan_id')) {
            // Menggunakan Scope yang sudah kita buat di Model
            $query->fromAngkatan($request->input('angkatan_id'));
        } 
        // Skenario B: Frontend tidak mengirim apa-apa (Halaman pertama load)
        else {
            // Otomatis tampilkan angkatan yang sedang AKTIF menjabat
            // Jadi halaman tidak kosong, tapi langsung relevan.
            $query->currentActive();
        }
        
        // ---------------------------------------------

        return $query->get();
    }

    /**
     * Menyimpan anggota tim baru.
     */
    public function store(Request $request)
    {
        Log::info('Store request data (raw):', $request->all());

        // --- PRE-PROCESSING ---
        $data = $request->all();
        if ($request->has('social_links') && is_string($request->input('social_links'))) {
            $data['social_links'] = json_decode($request->input('social_links'), true);
        }
        if ($request->has('bio_data') && is_string($request->input('bio_data'))) {
            $data['bio_data'] = json_decode($request->input('bio_data'), true);
        }
        $request->replace($data);
        // ---------------------
        
        try {
            $validated = $request->validate([
                // WAJIB: Setiap anggota baru HARUS punya angkatan
                'angkatan_id' => 'required|exists:angkatans,id', 
                
                'name' => 'required|string|max:255',
                'position' => 'required|string|max:255',
                'order' => 'required|integer|min:0',
                'photo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'header_photo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'social_links' => 'nullable|array', 
                'social_links.*.platform' => 'sometimes|required|string',
                'social_links.*.url' => 'sometimes|required|string',
                'bio_data' => 'nullable|array', 
                'bio_data.jurusan' => 'nullable|string|max:255',
                'bio_data.ttl' => 'nullable|string|max:255',
                'bio_data.hobi' => 'nullable|string|max:255',
                'bio_data.sambutan' => 'nullable|string',
                'bio_data.visi_misi' => 'nullable|string',
                'bio_data.prestasi' => 'nullable|array',
                'bio_data.prestasi.*' => 'nullable|string',
            ]);

            Log::info('Store validated data:', $validated);

            if ($request->hasFile('photo_file')) {
                $validated['photo_path'] = $request->file('photo_file')->store('team_photos', 'public');
            }
            if ($request->hasFile('header_photo_file')) {
                $validated['header_photo_path'] = $request->file('header_photo_file')->store('team_headers', 'public');
            }

            unset($validated['photo_file']);
            unset($validated['header_photo_file']);
            
            $teamMember = new TeamMember($validated);
            $teamMember->member_token = (string) Str::uuid(); 
            $teamMember->save(); 
            
            return response()->json(['message' => 'Anggota tim baru berhasil ditambahkan!', 'data' => $teamMember], 201);

        } catch (ValidationException $e) {
            Log::error('Store validation failed:', $e->errors());
            return response()->json(['message' => 'Data tidak valid.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error storing team:', ['message' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan server.'], 500);
        }
    }

    /**
     * Menampilkan detail satu anggota.
     */
    public function show(TeamMember $teamMember)
    {
        // Load workPrograms DAN info angkatan
        return $teamMember->load(['workPrograms', 'angkatan']);
    }

    /**
     * Memperbarui anggota tim yang ada.
     */
    public function update(Request $request, TeamMember $teamMember)
    {
        Log::info('Update request data (raw):', $request->all());

        $data = $request->all();
        if ($request->has('social_links') && is_string($request->input('social_links'))) {
            $data['social_links'] = json_decode($request->input('social_links'), true);
        }
        if ($request->has('bio_data') && is_string($request->input('bio_data'))) {
            $data['bio_data'] = json_decode($request->input('bio_data'), true);
        }
        $request->replace($data);

        try {
             $validated = $request->validate([
                // OPTIONAL: Admin boleh memindahkan anggota ke angkatan lain saat edit
                'angkatan_id' => 'sometimes|exists:angkatans,id',

                'name' => 'required|string|max:255',
                'position' => 'required|string|max:255',
                'order' => 'required|integer|min:0',
                'photo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'header_photo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'social_links' => 'nullable|array', 
                'social_links.*.platform' => 'sometimes|required|string',
                'social_links.*.url' => 'sometimes|required|string',
                'bio_data' => 'nullable|array', 
                'bio_data.jurusan' => 'nullable|string|max:255',
                'bio_data.ttl' => 'nullable|string|max:255',
                'bio_data.hobi' => 'nullable|string|max:255',
                'bio_data.sambutan' => 'nullable|string',
                'bio_data.visi_misi' => 'nullable|string',
                'bio_data.prestasi' => 'nullable|array',
                'bio_data.prestasi.*' => 'nullable|string',
            ]);

            Log::info('Update validated data:', $validated);

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

            unset($validated['photo_file']);
            unset($validated['header_photo_file']);

            $teamMember->update($validated);
            // Load ulang data termasuk relasi angkatan untuk dikembalikan ke frontend
            $updatedMember = $teamMember->fresh(['angkatan']);
            
            Log::info('Updated team member data:', $updatedMember->toArray());

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

    // ... (Method destroy, generateQrCode, servePhoto SAMA SEPERTI SEBELUMNYA) ...
    
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

    public function generateQrCode(TeamMember $teamMember)
    {
        $token = $teamMember->member_token;

        if (!$token) {
            return response()->json([
                'message' => 'Token untuk anggota ini belum di-generate. Coba jalankan "php artisan members:generate-tokens"'
            ], 404);
        }

        $svg = QrCode::format('svg')
                     ->size(250) 
                     ->margin(1)
                     ->generate($token);

        return response($svg)->header('Content-Type', 'image/svg+xml');
    }

    public function servePhoto(TeamMember $teamMember)
    {
        $path = $teamMember->photo_path;

        if (!$path || !Storage::disk('public')->exists($path)) {
            abort(404, 'File foto tidak ditemukan.');
        }

        $file = Storage::disk('public')->get($path);
        $mimeType = Storage::disk('public')->mimeType($path);

        return response($file, 200)->header('Content-Type', $mimeType);
    }
}
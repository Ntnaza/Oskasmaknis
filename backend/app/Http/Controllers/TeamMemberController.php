<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // <-- Pastikan ini ada
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str; 
use SimpleSoftwareIO\QrCode\Facades\QrCode; 

class TeamMemberController extends Controller
{
    /**
     * Menampilkan daftar semua anggota tim.
     */
    public function index(Request $request)
    {
        $query = TeamMember::orderBy('order', 'asc');

        // --- PERBAIKAN LOGIKA FILTER ---
        // Jika ada parameter 'angkatan_id' di URL, kita filter.
        // Jika tidak ada, kita KOSONGKAN hasilnya (agar data tidak campur aduk).
        
        if ($request->filled('angkatan_id')) {
            $query->where('angkatan_id', $request->input('angkatan_id'));
        } else {
            // Opsional: Jika tidak ada filter angkatan, jangan tampilkan apa-apa
            // Ini mencegah admin bingung melihat data campur aduk
            // return []; // Uncomment baris ini jika ingin tabel kosong saat tidak ada filter
        }
        // ------------------------------------

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
                // --- TAMBAHKAN VALIDASI INI ---
                'angkatan_id' => 'required|exists:angkatans,id', 
                // ------------------------------
                
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
            
            // Simpan data (angkatan_id sudah termasuk di $validated)
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
     * Menampilkan detail satu anggota tim beserta program kerjanya.
     */
    public function show(TeamMember $teamMember)
    {
        return $teamMember->load('workPrograms');
    }

    /**
     * Memperbarui anggota tim yang ada.
     */
    public function update(Request $request, TeamMember $teamMember)
    {
        Log::info('Update request data (raw):', $request->all());

        // --- PRE-PROCESSING DATA FORM ---
        $data = $request->all();
        if ($request->has('social_links') && is_string($request->input('social_links'))) {
            $data['social_links'] = json_decode($request->input('social_links'), true);
        }
        if ($request->has('bio_data') && is_string($request->input('bio_data'))) {
            $data['bio_data'] = json_decode($request->input('bio_data'), true);
        }
        $request->replace($data);
        // --- BATAS PRE-PROCESSING ---

        try {
             $validated = $request->validate([
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
            $updatedMember = $teamMember->fresh();
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

    /**
     * Men-generate dan mengembalikan gambar QR code (SVG) untuk anggota.
     */
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

    // --- METHOD BARU UNTUK SERVE FOTO (SOLUSI CORS) ---

    /**
     * Menyajikan file foto anggota dengan header CORS yang benar.
     */
    public function servePhoto(TeamMember $teamMember)
    {
        $path = $teamMember->photo_path;

        // Cek apakah file ada di disk 'public'
        if (!$path || !Storage::disk('public')->exists($path)) {
            // Jika tidak ada, bisa kembalikan 404
            abort(404, 'File foto tidak ditemukan.');
        }

        // Ambil file dari storage
        $file = Storage::disk('public')->get($path);
        
        // Dapatkan tipe mime (misal: 'image/png')
        $mimeType = Storage::disk('public')->mimeType($path);

        // Kembalikan file sebagai respons yang benar
        // Respons ini akan otomatis melewati middleware CORS
        return response($file, 200)->header('Content-Type', $mimeType);
    }
}
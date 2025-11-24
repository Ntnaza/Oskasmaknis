<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Aspiration;
use App\Models\Angkatan; // <-- Import ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AspirationController extends Controller
{
    /**
     * [ADMIN] Menampilkan daftar aspirasi (Difilter per Angkatan).
     */
    public function index(Request $request)
    {
        $query = Aspiration::orderBy('created_at', 'desc');

        // --- LOGIKA FILTER ANGKATAN ---
        if ($request->filled('angkatan_id')) {
            $query->where('angkatan_id', $request->input('angkatan_id'));
        } else {
            // Fallback: Ambil angkatan aktif
            $active = Angkatan::where('is_active', true)->first();
            if ($active) $query->where('angkatan_id', $active->id);
        }
        // ------------------------------

        return response()->json($query->paginate(20));
    }

    /**
     * [PUBLIK] Menyimpan aspirasi baru dari siswa.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'message' => 'required|string|max:500',
            'name' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:jpg,png,pdf,doc,docx|max:5120',
            'rating' => 'required|integer|min:1|max:5',
            // angkatan_id opsional di request, kalau null kita cari yang aktif
            'angkatan_id' => 'nullable|exists:angkatans,id', 
        ]);

        // --- TENTUKAN ANGKATAN ---
        $angkatanId = $request->input('angkatan_id');
        if (!$angkatanId) {
            // Jika frontend tidak kirim ID, masukkan ke angkatan yang sedang aktif
            $active = Angkatan::where('is_active', true)->first();
            $angkatanId = $active ? $active->id : null;
        }

        if (!$angkatanId) {
            return response()->json(['message' => 'Sistem belum memiliki angkatan aktif.'], 400);
        }
        // -------------------------

        $filePath = null;
        if ($request->hasFile('attachment')) {
            $filePath = $request->file('attachment')->store('aspirations_attachments', 'public');
        }

        $ticketId = 'ASP-' . now()->year . '-' . strtoupper(Str::random(4));
        while (Aspiration::where('ticket_id', $ticketId)->exists()) {
            $ticketId = 'ASP-' . now()->year . '-' . strtoupper(Str::random(4));
        }

        $aspiration = Aspiration::create([
            'angkatan_id' => $angkatanId, // <-- Simpan ID
            'ticket_id' => $ticketId,
            'subject' => $validatedData['subject'],
            'category' => $validatedData['category'],
            'message' => $validatedData['message'],
            'name' => $validatedData['name'] ?? null,
            'contact' => $validatedData['contact'] ?? null,
            'file_path' => $filePath,
            'status' => 'Baru',
            'rating' => $validatedData['rating'],
        ]);

        return response()->json([
            'message' => 'Aspirasi Anda telah berhasil dikirim!',
            'ticket_id' => $aspiration->ticket_id,
        ], 201);
    }

    /**
     * [PUBLIK] Melacak status aspirasi.
     */
    public function track($ticket_id)
    {
        $aspiration = Aspiration::where('ticket_id', $ticket_id)->first();
        if (!$aspiration) {
            return response()->json(['message' => 'Nomor tiket tidak ditemukan.'], 404);
        }
        return response()->json([
            'ticket_id' => $aspiration->ticket_id,
            'subject' => $aspiration->subject,
            'category' => $aspiration->category,
            'status' => $aspiration->status,
            'submitted_at' => $aspiration->created_at->diffForHumans(),
            // Bisa tambahkan info angkatan juga jika mau
            'angkatan' => $aspiration->angkatan ? $aspiration->angkatan->name : '-',
        ]);
    }

    /**
     * [ADMIN] Detail aspirasi.
     */
    public function show(Aspiration $aspiration)
    {
        return response()->json($aspiration);
    }

    /**
     * [ADMIN] Update status.
     */
    public function update(Request $request, Aspiration $aspiration)
    {
        $validatedData = $request->validate([
            'status' => 'required|string|in:Baru,Sudah Dibaca,Sedang Didiskusikan,Sudah Ditindaklanjuti,Selesai',
            'internal_notes' => 'nullable|string',
        ]);
        $aspiration->update($validatedData);
        
        return response()->json([
            'message' => 'Status aspirasi berhasil diperbarui.',
            'data' => $aspiration,
        ]);
    }

    /**
     * [ADMIN] Hapus.
     */
    public function destroy(Aspiration $aspiration)
    {
        if ($aspiration->file_path) {
            Storage::disk('public')->delete($aspiration->file_path);
        }
        $aspiration->delete();
        return response()->json(['message' => 'Aspirasi berhasil dihapus.'], 200);
    }

    /**
     * [PUBLIK] Galeri Aspirasi (Difilter per Angkatan).
     */
    public function getPublicAspirations(Request $request)
    {
        $query = Aspiration::query();

        // --- FILTER ANGKATAN (Penting untuk Galeri di Landing Page) ---
        if ($request->filled('angkatan_id')) {
            $query->where('angkatan_id', $request->input('angkatan_id'));
        } else {
            $active = Angkatan::where('is_active', true)->first();
            if ($active) $query->where('angkatan_id', $active->id);
        }
        // ------------------------------------------------------------

        $query->where('status', '!=', 'Baru');

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('subject', 'like', '%' . $searchTerm . '%')
                  ->orWhere('message', 'like', '%' . $searchTerm . '%');
            });
        }

        $aspirations = $query->select('id', 'subject', 'category', 'message', 'status', 'created_at', 'rating')
                             ->orderBy('created_at', 'desc')
                             ->paginate(6);

        return response()->json($aspirations);
    }
}
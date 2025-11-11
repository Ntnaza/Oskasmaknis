<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Aspiration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AspirationController extends Controller
{
    /**
     * [ADMIN] Menampilkan daftar semua aspirasi.
     */
    public function index()
    {
        // ... (Method index Anda yang sudah ada)
        $aspirations = Aspiration::orderBy('created_at', 'desc')->paginate(20);
        return response()->json($aspirations);
    }

    /**
     * [PUBLIK] Menyimpan aspirasi baru dari siswa.
     */
    public function store(Request $request)
    {
        // [UPDATE 1] Tambahkan 'rating' ke validasi
        $validatedData = $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'message' => 'required|string|max:500',
            'name' => 'nullable|string|max:255',
            'contact' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:jpg,png,pdf,doc,docx|max:5120',
            'rating' => 'required|integer|min:1|max:5', // <-- DIPERBARUI
        ]);

        $filePath = null;
        if ($request->hasFile('attachment')) {
            $filePath = $request->file('attachment')->store('aspirations_attachments', 'public');
        }

        $ticketId = 'ASP-' . now()->year . '-' . strtoupper(Str::random(4));
        while (Aspiration::where('ticket_id', $ticketId)->exists()) {
            $ticketId = 'ASP-' . now()->year . '-' . strtoupper(Str::random(4));
        }

        // [UPDATE 2] Tambahkan 'rating' saat menyimpan data
        $aspiration = Aspiration::create([
            'ticket_id' => $ticketId,
            'subject' => $validatedData['subject'],
            'category' => $validatedData['category'],
            'message' => $validatedData['message'],
            'name' => $validatedData['name'] ?? null,
            'contact' => $validatedData['contact'] ?? null,
            'file_path' => $filePath,
            'status' => 'Baru',
            'rating' => $validatedData['rating'], // <-- DIPERBARUI
        ]);

        return response()->json([
            'message' => 'Aspirasi Anda telah berhasil dikirim!',
            'ticket_id' => $aspiration->ticket_id,
        ], 201);
    }

    /**
     * [PUBLIK] Melacak status aspirasi berdasarkan Nomor Tiket.
     */
    public function track($ticket_id)
    {
        // ... (Method track Anda yang sudah ada)
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
        ]);
    }


    /**
     * [ADMIN] Menampilkan detail satu aspirasi.
     */
    public function show(Aspiration $aspiration)
    {
        // ... (Method show Anda yang sudah ada)
        return response()->json($aspiration);
    }

    /**
     * [ADMIN] Mengupdate status atau catatan internal.
     */
    public function update(Request $request, Aspiration $aspiration)
    {
        // ... (Method update Anda yang sudah ada)
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
     * [ADMIN] Menghapus aspirasi.
     */
    public function destroy(Aspiration $aspiration)
    {
        // ... (Method destroy Anda yang sudah ada)
        if ($aspiration->file_path) {
            Storage::disk('public')->delete($aspiration->file_path);
        }
        $aspiration->delete();
        return response()->json(['message' => 'Aspirasi berhasil dihapus.'], 200);
    }


    // --- 2. METHOD BARU UNTUK GALERI PUBLIK ---
    
    /**
     * [PUBLIK] Mengambil aspirasi yang sudah dimoderasi (bukan 'Baru')
     * untuk galeri publik.
     */
    public function getPublicAspirations(Request $request)
    {
        // Mulai query
        $query = Aspiration::query();

        // Filter 1: HANYA tampilkan yang statusnya BUKAN 'Baru'
        $query->where('status', '!=', 'Baru');

        // Filter 2: Filter berdasarkan Kategori
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // Filter 3: Filter Search Bar
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('subject', 'like', '%' . $searchTerm . '%')
                  ->orWhere('message', 'like', '%' . $searchTerm . '%');
            });
        }

        // [UPDATE 3] Gunakan select() untuk keamanan dan performa
        $aspirations = $query->select(
                                'id', 'subject', 'category', 'message', 'status', 'created_at', 'rating'
                            )
                            ->orderBy('created_at', 'desc')
                            ->paginate(6); // 6 per halaman

        return response()->json($aspirations);
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TeamMember; // Pastikan ini ada
use App\Models\Attendance; // Pastikan ini ada
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon; // Pastikan ini ada

class EventController extends Controller
{
    /**
     * Menampilkan daftar semua event.
     */
    public function index()
    {
        $events = Event::orderBy('event_date', 'desc')->get();
        return response()->json($events);
    }

    /**
     * Menyimpan event baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
        ]);

        $qr_token = (string) Str::uuid();

        $event = Event::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'event_date' => $validatedData['event_date'],
            'qr_token' => $qr_token, 
            'status' => 'pending',
        ]);

        return response()->json($event, 201);
    }

    /**
     * Menampilkan satu event spesifik.
     */
    public function show(Event $event)
    {
        // Muat relasi 'attendances' dan sub-relasi 'teamMember'
        $eventData = $event->load('attendances.teamMember');
        
        // --- TAMBAHAN BARU ---
        // Ambil juga SEMUA anggota untuk perbandingan
        $allMembers = TeamMember::orderBy('name', 'asc')->get();
        // --- BATAS TAMBAHAN ---

        // Kirim keduanya dalam format JSON baru
        return response()->json([
            'event' => $eventData,
            'all_members' => $allMembers
        ]);
    }

    /**
     * Memperbarui event yang ada di database.
     */
    public function update(Request $request, Event $event)
    {
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'sometimes|required|date',
            'status' => [
                'sometimes',
                'required',
                'string',
                Rule::in(['pending', 'active', 'finished']),
            ],
        ]);

        $event->update($validatedData);
        return response()->json($event);
    }

    /**
     * Menghapus event dari database.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json(null, 204);
    }

    /**
     * Men-generate dan mengembalikan gambar QR code (SVG) untuk event.
     * (Method ini tidak kita pakai lagi untuk scan, tapi biarkan)
     */
    public function generateQrCode(Event $event)
    {
        $token = $event->qr_token;
        if (!$token) {
            return response()->json(['message' => 'Event token not found.'], 404);
        }
        $svg = QrCode::format('svg')->size(300)->margin(1)->generate($token);
        return response($svg)->header('Content-Type', 'image/svg+xml');
    }


    // --- 4. INI METHOD BARU UNTUK SISTEM BARU KITA (SUDAH DIPERBAIKI) ---

    /**
     * Mencatat kehadiran anggota berdasarkan scan QR token anggota
     * oleh Admin.
     */
    public function scanMemberAttendance(Request $request, Event $event)
    {
        // 1. Validasi input: kita butuh 'qr_data' (sesuai kiriman Vue)
        $validated = $request->validate([
            'qr_data' => 'required|string', // <-- DIPERBAIKI
        ]);

        // -----------------
        // Kumpulan Cek Keamanan (Validasi)
        // -----------------

        // Cek 1: Apakah event ini statusnya 'active'?
        if ($event->status !== 'active') {
            return response()->json([
                'status' => 'error',
                'message' => 'Absensi untuk acara ini belum dibuka atau sudah ditutup.'
            ], 403); // 403 = Forbidden
        }

        // Cek 2: Cari anggota berdasarkan tokennya
        // ASUMSI: qr_data berisi ID anggota.
        // Jika berisi 'member_token', ganti 'id' menjadi 'member_token'
        $member = TeamMember::where('member_token', $validated['qr_data'])->first();// <-- DIPERBAIKI

        if (!$member) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR Anggota tidak valid atau tidak terdaftar.'
            ], 404); // 404 = Not Found
        }

        // Cek 3: Apakah anggota ini SUDAH absen sebelumnya?
        $alreadyAttended = Attendance::where('event_id', $event->id)
                                        ->where('team_member_id', $member->id)
                                        ->exists();
        
        if ($alreadyAttended) {
            return response()->json([
                'status' => 'warning', // Bukan error, tapi peringatan
                'message' => 'Anggota "' . $member->name . '" sudah tercatat hadir.'
            ], 409); // 409 = Conflict
        }

        // -----------------
        // LOLOS SEMUA VALIDASI
        // -----------------

        // 4. Catat Kehadiran
        try {
            // Kita pakai create() karena lebih bersih
            // Pastikan model Attendance kamu punya $fillable
            Attendance::create([
                'event_id' => $event->id,
                'team_member_id' => $member->id,
                'attended_at' => Carbon::now() // Set waktu sekarang
            ]);

            // 5. Beri respons sukses!
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil! "' . $member->name . '" dicatat hadir.',
                'member_name' => $member->name,
                'attended_at' => Carbon::now()->toDateTimeString()
            ], 201); // 201 = Created

        } catch (\Exception $e) {
            // Tangkap jika ada error (misal: MassAssignmentException)
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan internal: ' . $e->getMessage()
            ], 500);
        }
    }
}
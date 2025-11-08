<?php

// --- PERBAIKAN DI SINI ---
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Penting untuk otentikasi
use Carbon\Carbon; // Penting untuk mengelola waktu
// --- BATAS PERBAIKAN ---

class AttendanceController extends Controller
{
    /**
     * Menerima dan memproses scan QR code absensi.
     * Rute: POST /api/attendance/scan
     */
    public function scan(Request $request)
    {
        // 1. Validasi input: kita butuh 'qr_token'
        $validated = $request->validate([
            'qr_token' => 'required|string|uuid', // Kita validasi sebagai UUID
        ]);

        // 2. Cari Event berdasarkan QR Token
        $event = Event::where('qr_token', $validated['qr_token'])->first();

        // -----------------
        // Kumpulan Cek Keamanan (Validasi)
        // -----------------

        // Cek 1: Apakah event-nya ada?
        if (!$event) {
            return response()->json([
                'message' => 'QR Code tidak valid atau acara tidak ditemukan.'
            ], 404); // 404 = Not Found
        }

        // Cek 2: Apakah absensi sudah dibuka?
        if ($event->status !== 'active') {
            if ($event->status === 'pending') {
                return response()->json([
                    'message' => 'Absensi untuk acara ini belum dibuka.'
                ], 403); // 403 = Forbidden
            }
            if ($event->status === 'finished') {
                return response()->json([
                    'message' => 'Absensi untuk acara ini sudah ditutup.'
                ], 403);
            }
        }

        // 3. Dapatkan ID Anggota (TeamMember) yang sedang login
        
        // --- ASUMSI PENTING ---
        // Saya berasumsi model 'User' (yang login) memiliki relasi
        // ke 'TeamMember' dengan nama 'teamMember'.
        // Contoh: $user->teamMember->id
        // Jika relasinya beda, sesuaikan baris ini.
        $teamMember = Auth::user()->teamMember;

        if (!$teamMember) {
             return response()->json([
                'message' => 'Data anggota tidak ditemukan.'
            ], 404);
        }
        
        $teamMemberId = $teamMember->id;

        // Cek 3: Apakah anggota ini SUDAH absen sebelumnya?
        $alreadyAttended = Attendance::where('event_id', $event->id)
                                      ->where('team_member_id', $teamMemberId)
                                      ->exists();
        
        if ($alreadyAttended) {
            return response()->json([
                'message' => 'Anda sudah tercatat hadir di acara ini.'
            ], 409); // 409 = Conflict
        }

        // -----------------
        // LOLOS SEMUA VALIDASI
        // -----------------

        // 4. Catat Kehadiran
        try {
            Attendance::create([
                'event_id' => $event->id,
                'team_member_id' => $teamMemberId,
                'attended_at' => Carbon::now() // Set waktu sekarang
            ]);

            // 5. Beri respons sukses!
            return response()->json([
                'message' => 'Absensi berhasil!',
                'event_title' => $event->title,
                'attended_at' => Carbon::now()->toDateTimeString()
            ], 201); // 201 = Created

        } catch (\Exception $e) {
            // Tangkap jika ada error database (misal: foreign key, dll)
            return response()->json([
                'message' => 'Terjadi kesalahan saat mencatat absensi.'
            ], 500); // 500 = Internal Server Error
        }
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CalendarActivity;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarActivityController extends Controller
{
    /**
     * [PUBLIK] Menampilkan daftar kegiatan untuk FullCalendar.
     * FullCalendar akan mengirim parameter 'start' dan 'end'.
     */
    public function indexPublic(Request $request)
    {
        // Validasi input dari FullCalendar
        $validated = $request->validate([
            'start' => 'required|date',
            'end' => 'required|date',
        ]);

        // Cari semua kegiatan yang:
        // - Berakhir SETELAH tanggal 'start' kalender
        // - DAN Dimulai SEBELUM tanggal 'end' kalender
        // Ini adalah logika standar untuk mengambil event dalam rentang tanggal
        $activities = CalendarActivity::where('start_date', '<=', $validated['end'])
                                      ->where('end_date', '>=', $validated['start'])
                                      ->orWhere(function($query) use ($validated) {
                                          // Handle event yang tidak punya 'end_date' (satu hari)
                                          $query->whereNull('end_date')
                                                ->whereBetween('start_date', [$validated['start'], $validated['end']]);
                                      })
                                      ->get();
        
        // Kita ubah formatnya agar sesuai dengan yang diharapkan FullCalendar
        // (FullCalendar butuh 'title', 'start', 'end')
        $formattedActivities = $activities->map(function ($activity) {
            return [
                'id' => $activity->id,
                'title' => $activity->title,
                'start' => $activity->start_date,
                'end' => $activity->end_date, // FullCalendar bisa handle jika ini null
                'category' => $activity->category,
                'description' => $activity->description,
                // Kita bisa tambahkan warna berdasarkan kategori
                'color' => $this->getCategoryColor($activity->category),
            ];
        });

        return response()->json($formattedActivities);
    }

    /**
     * [ADMIN] Menampilkan daftar semua kegiatan (untuk tabel admin).
     */
    public function index()
    {
        // Tampilkan yang terbaru di atas, dengan pagination
        return CalendarActivity::orderBy('start_date', 'desc')->paginate(20);
    }

    /**
     * [ADMIN] Menyimpan kegiatan baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        // --- KONVERSI TIMEZONE ---
        // Ubah string "YYYY-MM-DDTHH:mm" dari 'Asia/Jakarta' ke UTC
        $validatedData['start_date'] = Carbon::parse($validatedData['start_date'], 'Asia/Jakarta')->setTimezone('UTC');
        if (!empty($validatedData['end_date'])) {
            $validatedData['end_date'] = Carbon::parse($validatedData['end_date'], 'Asia/Jakarta')->setTimezone('UTC');
        }
        // --- BATAS KONVERSI ---

        $activity = CalendarActivity::create($validatedData);

        return response()->json($activity, 201);
    }

    /**
     * [ADMIN] Menampilkan satu kegiatan spesifik.
     */
    public function show(CalendarActivity $calendarActivity)
    {
        // 'CalendarActivity $calendarActivity' adalah Route Model Binding
        return response()->json($calendarActivity);
    }

    /**
     * [ADMIN] Memperbarui kegiatan.
     */
    public function update(Request $request, CalendarActivity $calendarActivity)
    {
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|string|max:100',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        // --- KONVERSI TIMEZONE ---
        if (!empty($validatedData['start_date'])) {
            $validatedData['start_date'] = Carbon::parse($validatedData['start_date'], 'Asia/Jakarta')->setTimezone('UTC');
        }
        if (!empty($validatedData['end_date'])) {
            $validatedData['end_date'] = Carbon::parse($validatedData['end_date'], 'Asia/Jakarta')->setTimezone('UTC');
        }
        // --- BATAS KONVERSI ---

        $calendarActivity->update($validatedData);

        return response()->json($calendarActivity);
    }

    /**
     * [ADMIN] Menghapus kegiatan.
     */
    public function destroy(CalendarActivity $calendarActivity)
    {
        $calendarActivity->delete();
        return response()->json(null, 204); // 204 = No Content (sukses)
    }

    /**
     * Helper privat untuk memberi warna pada event kalender.
     */
    private function getCategoryColor($category)
    {
        switch ($category) {
            case 'Event Sekolah':
                return '#10B981'; // Hijau (Emerald)
            case 'Upacara':
                return '#EF4444'; // Merah (Red)
            case 'Lomba':
                return '#3B82F6'; // Biru (Blue)
            default:
                return '#6B7280'; // Abu-abu (Gray)
        }
    }
}
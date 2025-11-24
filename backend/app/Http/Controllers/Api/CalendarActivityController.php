<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CalendarActivity;
use App\Models\Angkatan; // <-- 1. Import Model Angkatan
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

        $query = CalendarActivity::query();

        // --- 2. LOGIKA FILTER ANGKATAN (CRITICAL) ---
        // Kita harus filter angkatan DULU sebelum filter tanggal
        if ($request->filled('angkatan_id')) {
            $query->where('angkatan_id', $request->input('angkatan_id'));
        } else {
            // Fallback ke angkatan aktif
            $active = Angkatan::where('is_active', true)->first();
            if ($active) {
                $query->where('angkatan_id', $active->id);
            }
        }
        // -------------------------------------------

        // Cari kegiatan yang beririsan dengan rentang tanggal kalender
        // Kita bungkus dalam where(function) agar logika OR tidak merusak filter angkatan
        $query->where(function($q) use ($validated) {
            $q->where('start_date', '<=', $validated['end'])
              ->where('end_date', '>=', $validated['start'])
              ->orWhere(function($subQ) use ($validated) {
                  // Handle event yang tidak punya 'end_date' (satu hari)
                  $subQ->whereNull('end_date')
                       ->whereBetween('start_date', [$validated['start'], $validated['end']]);
              });
        });
        
        $activities = $query->get();
        
        // Format untuk FullCalendar
        $formattedActivities = $activities->map(function ($activity) {
            return [
                'id' => $activity->id,
                'title' => $activity->title,
                'start' => $activity->start_date,
                'end' => $activity->end_date,
                'category' => $activity->category,
                'description' => $activity->description,
                'color' => $this->getCategoryColor($activity->category),
            ];
        });

        return response()->json($formattedActivities);
    }

    /**
     * [ADMIN] Menampilkan daftar semua kegiatan (untuk tabel admin).
     */
    public function index(Request $request)
    {
        $query = CalendarActivity::orderBy('start_date', 'desc');

        // --- FILTER ANGKATAN DI ADMIN ---
        if ($request->filled('angkatan_id')) {
            $query->where('angkatan_id', $request->input('angkatan_id'));
        }
        // --------------------------------

        return $query->paginate(20);
    }

    /**
     * [ADMIN] Menyimpan kegiatan baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'angkatan_id' => 'required|exists:angkatans,id', // <-- WAJIB ADA
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        // --- KONVERSI TIMEZONE ---
        $validatedData['start_date'] = Carbon::parse($validatedData['start_date'], 'Asia/Jakarta')->setTimezone('UTC');
        if (!empty($validatedData['end_date'])) {
            $validatedData['end_date'] = Carbon::parse($validatedData['end_date'], 'Asia/Jakarta')->setTimezone('UTC');
        }

        $activity = CalendarActivity::create($validatedData);

        return response()->json($activity, 201);
    }

    /**
     * [ADMIN] Menampilkan satu kegiatan spesifik.
     */
    public function show(CalendarActivity $calendarActivity)
    {
        return response()->json($calendarActivity);
    }

    /**
     * [ADMIN] Memperbarui kegiatan.
     */
    public function update(Request $request, CalendarActivity $calendarActivity)
    {
        $validatedData = $request->validate([
            'angkatan_id' => 'sometimes|exists:angkatans,id',
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

        $calendarActivity->update($validatedData);

        return response()->json($calendarActivity);
    }

    /**
     * [ADMIN] Menghapus kegiatan.
     */
    public function destroy(CalendarActivity $calendarActivity)
    {
        $calendarActivity->delete();
        return response()->json(null, 204);
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
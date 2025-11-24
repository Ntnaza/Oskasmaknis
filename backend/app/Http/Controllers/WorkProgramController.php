<?php

namespace App\Http\Controllers;

use App\Models\WorkProgram;
use App\Models\Angkatan; // <-- Jangan lupa import ini
use Illuminate\Http\Request;

class WorkProgramController extends Controller
{
    /**
     * Menampilkan daftar proker (difilter per angkatan).
     */
    public function index(Request $request)
    {
        // 1. Gunakan 'with' agar data penanggung jawab ikut terambil
        $query = WorkProgram::with('teamMember')->orderBy('start_date', 'desc');

        // 2. Logika Filter Angkatan
        if ($request->filled('angkatan_id')) {
            $query->where('angkatan_id', $request->input('angkatan_id'));
        } else {
            // Fallback: Ambil angkatan aktif jika filter kosong
            $active = Angkatan::where('is_active', true)->first();
            if ($active) {
                $query->where('angkatan_id', $active->id);
            }
        }

        return response()->json($query->get());
    }

    /**
     * Simpan proker baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // --- WAJIB: Pastikan angkatan_id divalidasi ---
            'angkatan_id' => 'required|exists:angkatans,id',
            
            'team_member_id' => 'required|exists:team_members,id', 
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $workProgram = WorkProgram::create($validated);
        
        return response()->json($workProgram, 201);
    }

    /**
     * Tampilkan detail 1 proker.
     */
    public function show(WorkProgram $workProgram)
    {
        return $workProgram->load('teamMember');
    }

    /**
     * Update proker.
     */
    public function update(Request $request, WorkProgram $workProgram)
    {
        $validated = $request->validate([
            'angkatan_id' => 'sometimes|exists:angkatans,id',
            'team_member_id' => 'required|exists:team_members,id', 
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $workProgram->update($validated);
        
        return response()->json($workProgram);
    }

    /**
     * Hapus proker.
     */
    public function destroy(WorkProgram $workProgram)
    {
        $workProgram->delete();
        return response()->json(['message' => 'Program kerja berhasil dihapus']);
    }

    /**
     * Method Tambahan: Untuk Dropdown Selection di tempat lain
     * (Opsional, tapi berguna jika nanti butuh list proker ringkas)
     */
    public function getAllForSelection(Request $request)
    {
        $query = WorkProgram::select('id', 'title');

        if ($request->filled('angkatan_id')) {
            $query->where('angkatan_id', $request->input('angkatan_id'));
        } else {
             $active = Angkatan::where('is_active', true)->first();
             if ($active) $query->where('angkatan_id', $active->id);
        }

        return response()->json(['data' => $query->orderBy('title')->get()]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\WorkProgram;
use Illuminate\Http\Request;

class WorkProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = WorkProgram::query(); 

        // --- LOGIKA FILTER ANGKATAN ---
        if ($request->filled('angkatan_id')) {
            $query->where('angkatan_id', $request->input('angkatan_id'));
        }
        // ------------------------------

        return response()->json($query->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // --- TAMBAHAN WAJIB: ID ANGKATAN ---
            'angkatan_id' => 'required|exists:angkatans,id',
            // -----------------------------------
            
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
     * Display the specified resource.
     */
    public function show(WorkProgram $workProgram)
    {
        return $workProgram;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkProgram $workProgram)
    {
        $validated = $request->validate([
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
     * Remove the specified resource from storage.
     */
    public function destroy(WorkProgram $workProgram)
    {
        $workProgram->delete();
        return response()->json(['message' => 'Program kerja berhasil dihapus']);
    }

    // TAMBAHKAN METHOD INI
    public function getAllForSelection(Request $request)
    {
        $query = WorkProgram::select('id', 'title');

        // Kita tambahkan filter angkatan di sini juga
        // Agar saat memilih proker (misal untuk di-highlight di landing page),
        // yang muncul hanya proker angkatan tersebut.
        if ($request->filled('angkatan_id')) {
            $query->where('angkatan_id', $request->input('angkatan_id'));
        }

        $workPrograms = $query->get();
        return response()->json(['data' => $workPrograms]);
    }
}
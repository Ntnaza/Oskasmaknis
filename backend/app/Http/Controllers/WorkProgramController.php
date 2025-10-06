<?php

namespace App\Http\Controllers;

use App\Models\WorkProgram;
use Illuminate\Http\Request;

class WorkProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua program kerja, urutkan dari yang terbaru
        return WorkProgram::orderBy('created_at', 'desc')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_member_id' => 'required|exists:team_members,id', // <-- Aturan baru
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
            'team_member_id' => 'required|exists:team_members,id', // <-- Aturan baru
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
}


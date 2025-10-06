<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WorkProgram;
use App\Models\TeamMember; // 1. Impor Model TeamMember

class WorkProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 2. Ambil semua anggota tim yang ada untuk dijadikan penanggung jawab
        $teamMembers = TeamMember::all();

        // Pastikan ada anggota tim sebelum membuat program kerja
        if ($teamMembers->isEmpty()) {
            // Beri pesan jika tidak ada anggota tim, agar tidak error
            $this->command->info('Tidak ada anggota tim, seeder program kerja dilewati.');
            return;
        }

        WorkProgram::updateOrCreate(
            ['title' => 'Lomba Kemerdekaan 17 Agustus'],
            [
                // 3. Tetapkan ID anggota tim secara acak sebagai penanggung jawab
                'team_member_id' => $teamMembers->random()->id,
                'description' => 'Serangkaian perlombaan antar kelas untuk memeriahkan Hari Kemerdekaan Republik Indonesia.',
                'status' => 'Selesai',
                'start_date' => '2025-08-15',
                'end_date' => '2025-08-17',
            ]
        );

        WorkProgram::updateOrCreate(
            ['title' => 'Class Meeting Semester Ganjil'],
            [
                'team_member_id' => $teamMembers->random()->id,
                'description' => 'Ajang kompetisi olahraga dan seni antar kelas setelah pelaksanaan Ujian Akhir Semester.',
                'status' => 'Direncanakan',
                'start_date' => '2025-12-15',
                'end_date' => '2025-12-18',
            ]
        );

        WorkProgram::updateOrCreate(
            ['title' => 'Peringatan Hari Guru Nasional'],
            [
                'team_member_id' => $teamMembers->random()->id,
                'description' => 'Mengadakan upacara bendera khusus dan memberikan apresiasi kepada para guru.',
                'status' => 'Berjalan',
                'start_date' => '2025-11-25',
                'end_date' => '2025-11-25',
            ]
        );
    }
}


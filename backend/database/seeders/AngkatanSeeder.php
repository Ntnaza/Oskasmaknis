<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Angkatan;

class AngkatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Angkatan Tahun Lalu (Biru)
        Angkatan::create([
            'name' => 'SARA BHAWANA', // Contoh nama
            'year_period' => '2023/2024',
            'is_active' => false,
            'theme_color' => '#3B82F6', // Blue-500
        ]);

        // 2. Angkatan SEKARANG (Hijau - JIVA ABISATYA) - AKTIF
        Angkatan::create([
            'name' => 'JIVA ABISATYA',
            'year_period' => '2024/2025',
            'is_active' => true, // <--- INI YANG AKTIF
            'theme_color' => '#10B981', // Emerald-500 (Warna default Vue Notus)
        ]);

        // 3. Angkatan Tahun Depan (Merah)
        Angkatan::create([
            'name' => 'RAKSAGANA', // Contoh nama
            'year_period' => '2025/2026',
            'is_active' => false,
            'theme_color' => '#EF4444', // Red-500
        ]);
    }
}
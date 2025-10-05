<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PageContent; // Jangan lupa impor Model-nya

class PageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Gunakan updateOrCreate agar tidak ada data duplikat jika seeder dijalankan berkali-kali
        PageContent::updateOrCreate(
            ['slug' => 'landing'], // Kunci untuk mencari data
            [
                'title' => 'OSIS & MPK',
                'subtitle' => 'Semua berawal dari sini'
            ]
        );

        // Kamu bisa tambahkan data untuk halaman lain di sini nanti
        // PageContent::updateOrCreate(
        //     ['slug' => 'about-us'],
        //     [
        //         'title' => 'Tentang Kami',
        //         'subtitle' => 'Sejarah singkat OSIS & MPK sekolah.'
        //     ]
        // );
    }
}
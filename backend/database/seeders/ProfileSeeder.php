<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Profile; // Jangan lupa impor Model-nya

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Gunakan updateOrCreate untuk memastikan hanya ada satu baris profil
        Profile::updateOrCreate(
            ['id' => 1], // Kunci untuk mencari: selalu baris pertama
            [
                'name' => 'OSIS & MPK SMK NURUL ISLAM',
                'location' => 'Cianjur, Indonesia',
                'about' => 'Tuliskan deskripsi, visi, misi, atau sejarah singkat tentang OSIS & MPK di sini. Teks ini bisa diubah kapan saja melalui halaman admin.',
                // Biarkan path gambar kosong untuk diisi dari halaman admin nanti
                'profile_image_path' => null,
                'header_image_path' => null,
            ]
        );
    }
}

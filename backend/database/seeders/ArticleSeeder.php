<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Article; // Jangan lupa impor Model-nya
use Illuminate\Support\Str; // Impor Str untuk membuat slug otomatis

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contoh 1: Artikel Berita Biasa
        $title1 = 'Peringatan Hari Kemerdekaan ke-80 Berlangsung Meriah';
        Article::updateOrCreate(
            ['slug' => Str::slug($title1)], // Membuat URL-friendly: 'peringatan-hari-kemerdekaan...'
            [
                'title' => $title1,
                'excerpt' => 'Seluruh siswa dan guru berpartisipasi dalam serangkaian lomba untuk memeriahkan HUT RI ke-80 di lingkungan sekolah.',
                'content' => '<p>Acara peringatan Hari Kemerdekaan Republik Indonesia ke-80 di sekolah kami berlangsung dengan sangat meriah. Dimulai dengan upacara bendera di pagi hari, acara dilanjutkan dengan berbagai perlombaan tradisional yang diikuti oleh siswa dari semua tingkatan kelas.</p><p>Kepala sekolah dalam sambutannya menekankan pentingnya meneladani semangat para pahlawan dalam mengisi kemerdekaan melalui kegiatan-kegiatan positif dan prestasi akademik.</p>',
                'featured_image_path' => 'articles/default-image.jpg', // Path gambar placeholder
                'gallery' => null, // Tidak ada galeri untuk artikel ini
                'published_at' => now(),
            ]
        );

        // Contoh 2: Galeri Kegiatan
        $title2 = 'Galeri Kegiatan Class Meeting Akhir Tahun';
        Article::updateOrCreate(
            ['slug' => Str::slug($title2)],
            [
                'title' => $title2,
                'excerpt' => 'Dokumentasi keseruan berbagai cabang lomba pada acara Class Meeting semester ini. Lihat foto-foto terbaik dari tim basket, futsal, dan seni.',
                'content' => '<p>Class Meeting akhir tahun telah sukses diselenggarakan. Berikut adalah galeri foto dari berbagai momen tak terlupakan selama acara berlangsung.</p>',
                'featured_image_path' => 'gallery/class-meeting-featured.jpg',
                'gallery' => [ // Mengisi data galeri dengan beberapa path gambar
                    'gallery/class-meeting-1.jpg',
                    'gallery/class-meeting-2.jpg',
                    'gallery/class-meeting-3.jpg',
                    'gallery/class-meeting-4.jpg',
                ],
                'published_at' => now()->subDays(5), // Dipublikasikan 5 hari yang lalu
            ]
        );
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// use App\Models\ContentBlock; // Kita akan gunakan DB Facade, jadi ini tidak wajib
use Illuminate\Support\Facades\DB; // <-- Import DB Facade

class ContentBlockSeeder extends Seeder
{
    public function run(): void
    {
        // Rencana #2: Sambutan Ketua OSIS & MPK (Gunakan updateOrInsert)
        DB::table('content_blocks')->updateOrInsert(
            [
                'section_key' => 'index-sambutan-ketua' // Kunci pencarian
            ],
            [
                'page_slug' => 'index',
                'content' => json_encode([ // Encode manual ke JSON
                    'title' => 'Sambutan dari Pimpinan Organisasi',
                    'ketua_osis_name' => 'Nama Ketua OSIS',
                    'ketua_osis_sambutan' => 'Isi sambutan, visi, dan misi singkat dari Ketua OSIS.',
                    'ketua_mpk_name' => 'Nama Ketua MPK',
                    'ketua_mpk_sambutan' => 'Isi sambutan, visi, dan misi singkat dari Ketua MPK.',
                    'ketua_mpk_image_path' => null,
                    'ketua_osis_image_path' => null,
                    'featured_work_program_ids' => [], // Ubah jadi _ids dan array kosong
                    'ketua_mpk_visi' => '', // Tambahkan Visi MPK kosong
                    'ketua_mpk_misi' => '', // Tambahkan Misi MPK kosong
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Data Pembina (Gunakan updateOrInsert)
        DB::table('content_blocks')->updateOrInsert(
             [
                'section_key' => 'index-pembina' // Kunci pencarian
            ],
            [
                'page_slug' => 'index',
                'content' => json_encode([ // Encode manual ke JSON
                    'title' => 'Dibimbing Oleh',
                    'pembina_name' => 'Nama Lengkap Pembina, S.Pd.',
                    'pembina_title' => 'Pembina OSIS & MPK',
                    'pembina_image_path' => null, // Ganti dari _url ke _path
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Kolaborasi Jurusan (Gunakan updateOrInsert)
        DB::table('content_blocks')->updateOrInsert(
            [
                'section_key' => 'index-kolaborasi-jurusan' // Kunci pencarian
            ],
            [
                'page_slug' => 'index',
                'content' => json_encode([ // Encode manual ke JSON
                    'title' => 'Kolaborasi Lintas Jurusan',
                    'description' => 'OSIS & MPK (OSKA) adalah wadah bagi siswa dari semua jurusan untuk berkolaborasi, berkreasi, dan berinovasi bersama.',
                    'jurusan_list' => [
                         ['name' => 'PPLG', 'description' => 'Pengembangan Perangkat Lunak & Gim', 'logo_filename' => 'PPLG.png', 'color' => 'bg-purple-500'], // Warna diubah
                         ['name' => 'TJKT', 'description' => 'Teknik Jaringan Komputer & Telekomunikasi', 'logo_filename' => 'TJKT.png', 'color' => 'bg-red-600'],
                         ['name' => 'OTOMOTIF', 'description' => 'Teknik Otomotif', 'logo_filename' => 'TO.png', 'color' => 'bg-blueGray-700'],
                         ['name' => 'DKV', 'description' => 'Desain Komunikasi Visual', 'logo_filename' => 'DKV.png', 'color' => 'bg-pink-500'], // Warna diubah
                         ['name' => 'MPLB', 'description' => 'Manajemen Perkantoran & Layanan Bisnis', 'logo_filename' => 'MPLB.png', 'color' => 'bg-lightBlue-500'],
                         ['name' => 'AKL', 'description' => 'Akuntansi & Keuangan Lembaga', 'logo_filename' => 'AKL.png', 'color' => 'bg-yellow-500']
                    ]
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Sejarah Singkat OSKA (Gunakan updateOrInsert)
        DB::table('content_blocks')->updateOrInsert(
            [
                'section_key' => 'index-sejarah-oska' // Kunci pencarian
            ],
            [
                'page_slug' => 'index',
                'content' => json_encode([ // Encode manual ke JSON
                    'title' => 'Sejarah Singkat OSKA',
                    'description' => 'Berawal dari semangat untuk memajukan kegiatan siswa, OSKA terus berevolusi menjadi organisasi yang modern, inovatif, dan berdampak bagi lingkungan sekolah.',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Ajakan Bergabung (Gunakan updateOrInsert)
        DB::table('content_blocks')->updateOrInsert(
            [
                'section_key' => 'index-ajakan-bergabung' // Kunci pencarian
            ],
            [
                'page_slug' => 'index',
                'content' => json_encode([ // Encode manual ke JSON
                    'title' => 'Jadilah Bagian dari Perubahan!',
                    'description' => 'Buktikan potensimu, asah jiwa kepemimpinanmu, dan berikan kontribusi nyata untuk sekolah. Ayo bergabung dengan keluarga besar OSIS & MPK!',
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // ======================================================
        // ==         TAMBAHKAN BLOK UNTUK PROMO SECTION         ==
        // ======================================================
        DB::table('content_blocks')->updateOrInsert(
            [
                'section_key' => 'promo-section' // Kunci pencarian
            ],
            [
                'page_slug' => 'landing', // Slug halaman landing
                'content' => json_encode([ // Encode manual ke JSON
                    'title' => 'Judul Promo Section Awal',
                    'description' => 'Deskripsi untuk bagian promo ini. Jelaskan keunggulan atau fitur utama.',
                    'icon' => 'fas fa-rocket', // Contoh ikon FontAwesome
                    'image_url' => null,     // Path gambar akan diisi via admin
                    'list_items' => [
                        ['icon' => 'fas fa-fingerprint', 'text' => 'Fitur Keamanan Terjamin'],
                        ['icon' => 'fas fa-users', 'text' => 'Komunitas Aktif'],
                        ['icon' => 'fas fa-headset', 'text' => 'Dukungan Penuh'],
                        // Tambahkan poin lain jika perlu
                    ]
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        // ======================================================

    }
}
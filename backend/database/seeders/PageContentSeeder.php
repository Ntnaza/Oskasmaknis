<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PageContent;

class PageContentSeeder extends Seeder
{
    public function run(): void
    {
        // Data untuk Halaman Landing (biarkan saja)
        PageContent::updateOrCreate(
            ['slug' => 'landing'],
            [
                'title' => 'Kisah OSIS & MPK Dimulai Dari Sini',
                'subtitle' => 'Ini adalah halaman Landing Page dinamis...',
                'hero_image_url' => null,
            ]
        );

        // Data untuk Halaman Index (Seksi #1 Anda)
        PageContent::updateOrCreate(
            ['slug' => 'index'],
            [
                'title' => 'Selamat Datang di Website Resmi OSIS & MPK',
                'subtitle' => 'Ini adalah halaman perkenalan awal organisasi kami. Wadah aspirasi, kreasi, dan informasi untuk seluruh siswa.',
                'hero_image_url' => null,
            ]
        );
    }
}
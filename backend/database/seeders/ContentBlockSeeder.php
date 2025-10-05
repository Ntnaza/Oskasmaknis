<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ContentBlock;

class ContentBlockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContentBlock::updateOrCreate(
            ['section_key' => 'promo-section'],
            [
                'page_slug' => 'landing',
                'content' => [
                    'image_url' => 'promo-images/default.jpg', // Path default
                    'icon' => 'fas fa-rocket',
                    'title' => 'A growing company',
                    'description' => "The extension comes with three pre-built pages to help you get started faster. You can change the text and images and you're good to go.",
                    'list_items' => [
                        ['icon' => 'fas fa-fingerprint', 'text' => 'Carefully crafted components'],
                        ['icon' => 'fab fa-html5', 'text' => 'Amazing page examples'],
                        ['icon' => 'far fa-paper-plane', 'text' => 'Dynamic components'],
                    ]
                ]
            ]
        );
    }
}
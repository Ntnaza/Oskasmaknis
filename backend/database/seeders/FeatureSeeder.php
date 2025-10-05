<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Feature; // Impor model

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        Feature::updateOrCreate(['title' => 'Awarded Agency'], [
            'icon' => 'fas fa-award',
            'color' => 'red', // <-- TAMBAHKAN INI
            'description' => 'Divide details about your product or agency work into parts. A paragraph describing a feature will be enough.',
            'order' => 1
        ]);

        Feature::updateOrCreate(['title' => 'Free Revisions'], [
            'icon' => 'fas fa-retweet',
            'color' => 'lightBlue', // <-- TAMBAHKAN INI
            'description' => 'Keep you user engaged by providing meaningful information. Remember that by this time, the user is curious.',
            'order' => 2
        ]);
        
        Feature::updateOrCreate(['title' => 'Verified Company'], [
            'icon' => 'fas fa-fingerprint',
            'color' => 'emerald', // <-- TAMBAHKAN INI
            'description' => 'Write a few lines about each one. A paragraph describing a feature will be enough. Keep you user engaged!',
            'order' => 3
        ]);
    }
}
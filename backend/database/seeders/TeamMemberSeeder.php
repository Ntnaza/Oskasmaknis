<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TeamMember; // Jangan lupa impor Model-nya

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TeamMember::updateOrCreate(['name' => 'Ryan Tompson'], [
            'position' => 'Web Developer',
            'photo_path' => 'team-photos/default-1.jpg',
            'social_links' => [
                'twitter' => 'https://twitter.com',
                'facebook' => 'https://facebook.com',
                'dribbble' => 'https://dribbble.com',
            ],
            'order' => 1
        ]);

        TeamMember::updateOrCreate(['name' => 'Romina Hadid'], [
            'position' => 'Marketing Specialist',
            'photo_path' => 'team-photos/default-2.jpg',
            'social_links' => [
                'google' => 'https://google.com',
                'facebook' => 'https://facebook.com',
            ],
            'order' => 2
        ]);

        TeamMember::updateOrCreate(['name' => 'Alexa Smith'], [
            'position' => 'UI/UX Designer',
            'photo_path' => 'team-photos/default-3.jpg',
            'social_links' => [
                'google' => 'https://google.com',
                'twitter' => 'https://twitter.com',
                'instagram' => 'https://instagram.com',
            ],
            'order' => 3
        ]);

        TeamMember::updateOrCreate(['name' => 'Jenna Kardi'], [
            'position' => 'Founder and CEO',
            'photo_path' => 'team-photos/default-4.jpg',
            'social_links' => [
                'dribbble' => 'https://dribbble.com',
                'google' => 'https://google.com',
                'twitter' => 'https://twitter.com',
                'instagram' => 'https://instagram.com',
            ],
            'order' => 4
        ]);
    }
}

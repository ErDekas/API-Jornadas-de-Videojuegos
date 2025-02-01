<?php

// database/seeders/SpeakerSeeder.php
namespace Database\Seeders;

use App\Models\Speaker;
use Illuminate\Database\Seeder;

class SpeakerSeeder extends Seeder
{
    public function run()
    {
        $speakers = [
            [
                'name' => 'Ana García',
                'photo_url' => 'speakers/ana.jpg',
                'social_links' => json_encode([
                    'linkedin' => 'https://linkedin.com/in/ana-garcia',
                    'twitter' => 'https://twitter.com/anagarcia',
                    'github' => 'https://github.com/anagarcia'
                ]),
                'expertise_areas' => json_encode(['Unity', 'C#', 'Game Design'])
            ],
            [
                'name' => 'Carlos Rodríguez',
                'photo_url' => 'speakers/carlos.jpg',
                'social_links' => json_encode([
                    'linkedin' => 'https://linkedin.com/in/carlos-rodriguez',
                    'github' => 'https://github.com/carlosrodriguez'
                ]),
                'expertise_areas' => json_encode(['Unreal Engine', 'C++', '3D Modeling'])
            ],
            [
                'name' => 'Laura Martínez',
                'photo_url' => 'speakers/laura.jpg',
                'social_links' => json_encode([
                    'linkedin' => 'https://linkedin.com/in/laura-martinez',
                    'twitter' => 'https://twitter.com/lauramartinez'
                ]),
                'expertise_areas' => json_encode(['JavaScript', 'WebGL', 'HTML5 Games'])
            ],
            [
                'name' => 'David Sánchez',
                'photo_url' => 'speakers/david.jpg',
                'social_links' => json_encode([
                    'linkedin' => 'https://linkedin.com/in/david-sanchez',
                    'github' => 'https://github.com/davidsanchez'
                ]),
                'expertise_areas' => json_encode(['Game AI', 'Python', 'Machine Learning'])
            ]
        ];

        foreach ($speakers as $speaker) {
            Speaker::create($speaker);
        }
    }
}
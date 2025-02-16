<?php
namespace Database\Seeders;

use App\Models\Event;
use App\Models\Speaker;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run()
    {
        // Conferencias día 1 (Jueves)
        $events = [
            [
                'title' => 'El Futuro de los Videojuegos con Unity',
                'description' => 'Exploraremos las últimas tendencias y características de Unity para el desarrollo de videojuegos modernos.',
                'type' => 'conference',
                'date' => '2024-03-14',
                'start_time' => '10:00',
                'end_time' => '10:55',
                'max_attendees' => 100,
                'location' => 'auditorium',
                'speaker_ids' => [1] // Ana García
            ],
            [
                'title' => 'Desarrollo de Juegos con Unreal Engine 5',
                'description' => 'Masterclass sobre el desarrollo de videojuegos AAA con Unreal Engine 5.',
                'type' => 'conference',
                'date' => '2024-03-14',
                'start_time' => '11:00',
                'end_time' => '11:55',
                'max_attendees' => 100,
                'location' => 'auditorium',
                'speaker_ids' => [2] // Carlos Rodríguez
            ],
            // Talleres día 1
            [
                'title' => 'Taller Práctico de Unity',
                'description' => 'Taller hands-on para crear tu primer juego en Unity.',
                'type' => 'workshop',
                'date' => '2024-03-14',
                'start_time' => '10:00',
                'end_time' => '10:55',
                'max_attendees' => 20,
                'location' => 'classroom',
                'speaker_ids' => [1] // Ana García
            ],
            [
                'title' => 'Desarrollo de Juegos HTML5',
                'description' => 'Aprende a crear juegos para navegador usando tecnologías web modernas.',
                'type' => 'workshop',
                'date' => '2024-03-14',
                'start_time' => '11:00',
                'end_time' => '11:55',
                'max_attendees' => 20,
                'location' => 'classroom',
                'speaker_ids' => [3] // Laura Martínez
            ],

            // Conferencias día 2 (Viernes)
            [
                'title' => 'Inteligencia Artificial en Videojuegos',
                'description' => 'Implementación de IA avanzada en NPCs y comportamientos de juego.',
                'type' => 'conference',
                'date' => '2024-03-15',
                'start_time' => '12:00',
                'end_time' => '12:55',
                'max_attendees' => 100,
                'location' => 'auditorium',
                'speaker_ids' => [4] // David Sánchez
            ],
            [
                'title' => 'El Futuro del Gaming Web',
                'description' => 'Tendencias y tecnologías emergentes en el desarrollo de juegos web.',
                'type' => 'conference',
                'date' => '2024-03-15',
                'start_time' => '13:00',
                'end_time' => '13:55',
                'max_attendees' => 100,
                'location' => 'auditorium',
                'speaker_ids' => [3] // Laura Martínez
            ],
            // Talleres día 2
            [
                'title' => 'Taller de IA para Juegos',
                'description' => 'Implementación práctica de algoritmos de IA en videojuegos.',
                'type' => 'workshop',
                'date' => '2024-03-15',
                'start_time' => '12:00',
                'end_time' => '12:55',
                'max_attendees' => 20,
                'location' => 'classroom',
                'speaker_ids' => [4] // David Sánchez
            ],
            [
                'title' => 'Desarrollo Avanzado con Unreal',
                'description' => 'Taller práctico de características avanzadas de Unreal Engine.',
                'type' => 'workshop',
                'date' => '2024-03-15',
                'start_time' => '13:00',
                'end_time' => '13:55',
                'max_attendees' => 20,
                'location' => 'classroom',
                'speaker_ids' => [2] // Carlos Rodríguez
            ]
        ];

        foreach ($events as $eventData) {
            $speakerIds = $eventData['speaker_ids'];
            unset($eventData['speaker_ids']);
            
            $event = Event::create($eventData);
            
            // Asociar ponentes al evento
            $event->speakers()->attach($speakerIds);
        }
    }
}
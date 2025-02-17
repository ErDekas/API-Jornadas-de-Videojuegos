<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'photo_url',
        'social_links',
        'expertise_areas'
    ];

    protected $casts = [
        'social_links' => 'array',
        'expertise_areas' => 'array'
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_speaker')
            ->withTimestamps();
    }
}

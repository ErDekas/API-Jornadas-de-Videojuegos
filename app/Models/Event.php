<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type', // Conferencia o Workshop
        'date',
        'start_time',
        'end_time',
        'max_attendees',
        'current_attendees',
        'location' // Clase o Auditorio
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];

    public function speakers()
    {
        return $this->belongsToMany(Speaker::class, 'event_speaker')
            ->withTimestamps();
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'event_user')
            ->withTimestamps()
            ->withPivot('attended');
    }
}

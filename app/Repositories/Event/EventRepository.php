<?php

namespace App\Repositories\Event;

use App\Models\Event;
use Illuminate\Support\Collection;

class EventRepository implements EventRepositoryInterface
{
    public function getAll(): Collection
    {
        return Event::all();
    }

    public function findById(int $id): ?Event
    {
        return Event::find($id);
    }

    public function create(array $data): Event
    {
        return Event::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $event = Event::find($id);
        return $event ? $event->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $event = Event::find($id);
        return $event ? $event->delete() : false;
    }
}

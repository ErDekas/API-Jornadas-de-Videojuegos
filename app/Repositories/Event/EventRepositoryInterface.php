<?php

namespace App\Repositories\Event;

use App\Models\Event;
use Illuminate\Support\Collection;

interface EventRepositoryInterface
{
    public function getAll(): Collection;
    public function findById(int $id): ?Event;
    public function create(array $data): Event;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}

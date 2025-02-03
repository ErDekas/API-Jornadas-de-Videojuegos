<?php

namespace App\Repositories\Speaker;

use App\Models\Speaker;

class SpeakerRepository implements SpeakerRepositoryInterface
{
    protected $model;

    public function __construct(Speaker $speaker)
    {
        $this->model = $speaker;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $speaker = $this->model->find($id);
        if ($speaker) {
            $speaker->update($data);
            return $speaker;
        }
        return null;
    }

    public function delete($id)
    {
        $speaker = $this->model->find($id);
        if ($speaker) {
            return $speaker->delete();
        }
        return false;
    }
}

<?php

namespace App\Repositories\Registration;

use App\Models\Registration;

class RegistrationRepository implements RegistrationRepositoryInterface
{
    protected $model;

    public function __construct(Registration $registration)
    {
        $this->model = $registration;
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
        $registration = $this->model->find($id);
        if ($registration) {
            $registration->update($data);
            return $registration;
        }
        return null;
    }

    public function delete($id)
    {
        $registration = $this->model->find($id);
        if ($registration) {
            return $registration->delete();
        }
        return false;
    }

    public function findByTicketCode($ticketCode)
    {
        return $this->model->where('ticket_code', $ticketCode)->first();
    }
}

<?php

namespace App\Repositories\Payment;

use App\Models\Payment;

class PaymentRepository implements PaymentRepositoryInterface
{
    protected $model;

    public function __construct(Payment $payment)
    {
        $this->model = $payment;
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
        $payment = $this->model->find($id);
        if ($payment) {
            $payment->update($data);
            return $payment;
        }
        return null;
    }

    public function delete($id)
    {
        $payment = $this->model->find($id);
        if ($payment) {
            return $payment->delete();
        }
        return false;
    }

    public function findByTransactionOrPaypalOrderId($transactionId, $paypalOrderId)
    {
        return Payment::where('transaction_id', $transactionId)
            ->orWhere('paypal_order_id', $paypalOrderId)
            ->first();
    }

    public function process(array $data)
    {
        return Payment::create($data);
    }

}

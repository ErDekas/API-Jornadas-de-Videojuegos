<?php

namespace App\Repositories\Payment;

interface PaymentRepositoryInterface
{
    public function getAll();
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function findByTransactionOrPaypalOrderId($transactionId, $paypalOrderId);
    public function process(array $data);
}

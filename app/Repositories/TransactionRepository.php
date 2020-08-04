<?php

namespace App\Repositories;

use App\Transaction;

class TransactionRepository
{
    private $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function store($payer_id, $payee_id, $value)
    {
        $this->transaction->payer_id = $payer_id;
        $this->transaction->payee_id = $payee_id;
        $this->transaction->value = $value;
        return $this->transaction->save();
    }
}

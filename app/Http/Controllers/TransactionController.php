<?php

namespace App\Http\Controllers;

use App\Services\SendTransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function store(Request $request, SendTransactionService $service)
    {
        $rules = [
            'value' => 'required|numeric|min:0.01',
            'payer' => 'required|exists:users,id',
            'payee' => 'required|exists:users,id|different:payer'
        ];
        $data = $request->validate($rules);
        return $service->execute($data);
    }
}

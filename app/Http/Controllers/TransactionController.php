<?php

namespace App\Http\Controllers;

use App\Services\SendTransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * @OA\Info(
     *      title="PP Backend", 
     *      version="1.0.0",
     *      description="Desafio PP Backend",
     *      @OA\Contact(
     *          email="zepaduajr@gmail.com",
     *          name="Jose Guilherme Padua Jr."
     *      )),
     * @OA\Post(
     *     path="/api/transaction",
     *     description="Transfer transaction between users or user and company.",
     *     @OA\Parameter(
     *         name="value",
     *         in="query",
     *         @OA\Schema(
     *          type="number",
     *          format="float"
     *         ),
     *         description="Value to transfer. Must be greater than 0.",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="payer",
     *         in="query",
     *         @OA\Schema(
     *          type="integer",
     *         ),
     *         description="User (1 - 10)",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         name="payee",
     *         in="query",
     *         @OA\Schema(
     *          type="integer",
     *         ),
     *         description="User or Company (1 - 20)",
     *         required=true,
     *     ), 
     *     @OA\Response(
     *         response=200,
     *         description="Successful transaction",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Business validations"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Transaction Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Structural validations"
     *     )
     * ),
     * 
     */
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

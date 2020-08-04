<?php

namespace App\Services;

use App\Events\TransactionEvent;
use App\Http\Resources\MsgResource;
use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SendTransactionService
{
    private $transactionRepository, $userRepository, $requestMockyUrlService;

    public function __construct(TransactionRepository $transactionRepository, UserRepository $userRepository, RequestMockyUrlService $requestMockyUrlService)
    {
        $this->transactionRepository = $transactionRepository;
        $this->userRepository = $userRepository;
        $this->requestMockyUrlService = $requestMockyUrlService;
    }

    public function execute(array $data): JsonResponse
    {
        return DB::transaction(function () use ($data) {
            $payer = $this->userRepository->findByIdAndType($data['payer'], 'user');
            if (!$payer) {
                return MsgResource::make('Origin error', 400, 'The payer is not a user.');
            }

            if ($payer['balance'] < $data['value']) {
                return MsgResource::make('Value error', 400, 'Insufficient funds.');
            }

            $userPayer = $this->userRepository->updateBalanceById($payer['balance'] - $data['value'], $data['payer']);
            if (!$userPayer) {
                DB::rollBack();
                return MsgResource::make('Update error', 400, 'Update payer balance error. Try again.');
            }

            $request = $this->requestMockyUrlService->execute(env('URL_AUTORIZACAO'))->getOriginalContent();
            if($request['status'] != 200 || $request['details'] !== 'Autorizado') {
                DB::rollBack();
                return MsgResource::make('Authorization error', 401, 'Not Authorized.');
            }

            $payee = $this->userRepository->findById($data['payee']);
            $userPayee = $this->userRepository->updateBalanceById($payee['balance'] + $data['value'], $data['payee']);
            if (!$userPayee) {
                DB::rollBack();
                return MsgResource::make('Update error', 400, 'Update payee balance error. Try again.');
            }

            $transaction = $this->transactionRepository->store($data['payer'], $data['payee'], $data['value']);
            if (!$transaction) {
                DB::rollBack();
                return MsgResource::make('Transaction error', 400, 'Store transaction error. Try again.');
            }

            //TODO - Criar evento assincrono
            event(new TransactionEvent($payee['email']));

            return MsgResource::make('Success', 200, $transaction);
        });
    }
}

<?php

namespace App\Listeners;

use App\Services\RequestMockyUrlService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendNotificationListener
{
    private $requestMockyUrlService;

    public function __construct(RequestMockyUrlService $requestMockyUrlService)
    {
        $this->requestMockyUrlService = $requestMockyUrlService;
    }

    public function handle($event)
    {
        $email = $event->email;
        $request = $this->requestMockyUrlService->execute(env('URL_NOTIFICACAO'))->getOriginalContent();
        if ($request['status'] != 200 || $request['details'] !== 'Enviado') {
            Log::info('Notificação não enviada para o email ' . $email);
        } else {
            Log::info('Notificação enviada para o email ' . $email);
        }
    }
}

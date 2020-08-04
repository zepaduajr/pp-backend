<?php

namespace App\Services;

use App\Http\Resources\MsgResource;
use Illuminate\Http\JsonResponse;

class RequestMockyUrlService
{

    public function execute($url): JsonResponse
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', $url);
            $body = json_decode($response->getBody()->getContents())->message ?? '';

            if ($response->getStatusCode() != 200) {
                return MsgResource::make('Request error', 400, 'Request error.');
            }

            return MsgResource::make('Success', 200, $body);
        } catch (\Throwable $th) {
            return MsgResource::make('Request error', 500, 'Request error.');
        }
    }
}

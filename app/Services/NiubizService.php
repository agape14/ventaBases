<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class NiubizService
{
    protected $client;
    protected $merchantId;
    protected $accessKey;
    protected $secretKey;
    protected $baseUrl;

    public function generateToken()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . base64_encode(config('niubiz.user') . ":" . config('niubiz.password')),
            'Accept' => '*/*'
        ])->post(config('niubiz.security_url'));

        return $response->body();
    }

    public function generateSesion($amount, $token)
    {
        // Datos de la sesión
        $sessionData = [
            'amount' => $amount,
            'antifraud' => [
                'clientIp' => request()->ip(), // Obtener la IP del cliente de la solicitud
                'merchantDefineData' => [
                    'MDD4' => "mail@domain.com",
                    'MDD33' => "DNI",
                    'MDD34' => '87654321',
                ],
            ],
            'channel' => 'web',
        ];

        // Realizar la solicitud POST a la URL de sesión de Niubiz
        $response = $this->postRequest(config('niubiz.session_url').config('niubiz.merchantt_id'), $sessionData, $token);
        $responseBody = json_decode($response, true);
        return $responseBody['sessionKey'] ?? null;
    }

    public function postRequest($url, $postData, $token)
    {
        $response = Http::withHeaders([
            'Authorization' => $token,
            'Content-Type' => 'application/json',
        ])->post($url, $postData);

        return $response->body();
    }


    public function generateAuthorization($amount, $purchaseNumber, $transactionToken)
    {
        $token=$this->generateToken();
        $url=config('niubiz.authorization_url').config('niubiz.merchantt_id');
        $data = [
            'antifraud' => null,
            'captureType' => 'manual',
            'channel' => 'web',
            'countable' => true,
            'order' => [
                'amount' => $amount,
                'currency' => 'PEN',
                'purchaseNumber' => $purchaseNumber,
                'tokenId' => $transactionToken,
            ],
            'recurrence' => null,
            'sponsored' => null
        ];
        $response = $this->postRequest($url,$data,$token);
        $responseBody = json_decode($response, true);
        //dd($responseBody );
        return $responseBody;
    }

    public function generatePurchaseNumber()
    {
        $filePath = storage_path('app/purchaseNumber.txt');
        $purchaseNumber = 222;

        if (file_exists($filePath)) {
            $purchaseNumber = file_get_contents($filePath);
        }

        $purchaseNumber++;
        file_put_contents($filePath, $purchaseNumber);

        return $purchaseNumber;
    }
}


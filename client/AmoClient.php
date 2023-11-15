<?php

namespace Client;

use Client\Services\ContactService;
use Client\Services\LeadService;
use Exception;

class AmoClient
{
    private array $config = [];
    private string $url;
    private array $tokenData = [];

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->url = "https://{$this->config['subdomain']}.amocrm.ru/api/v4/";
    }

    public function contacts()
    {
        return new ContactService($this);
    }

    public function leads()
    {
        return new LeadService($this);
    }

    public function authByCode(string $code)
    {
        $tokenData = [
            'client_id' => $this->config['client_id'],
            'client_secret' => $this->config['client_secret'],
            'redirect_uri' => $this->config['redirect_uri'],
            'code' => $code,
            'grant_type' => 'authorization_code'
        ];

        $response = $this->queryAccessToken($tokenData);

        if ($response['status'] == 200) {
            $response['data']['date'] = time();

            $this->saveDataToken($response['data']);
            $this->tokenData = $response['data'];
        }

        return $response;
    }

    public function postQuery(string $path, array $data): array
    {
        if (empty($this->tokenData)) {
            $this->getDataToken();
        }

        if (!$this->isTokenExpired()) {
            $this->refreshAccessToken();
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $this->url . $path,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->tokenData['access_token'],
                'Content-Type: application/json'
            ],
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
        ]);
        
        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return $this->curlResponse($response, $status);
    }

    private function queryAccessToken($data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => 'https://' . $this->config['subdomain'] . '.amocrm.ru/oauth2/access_token',
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
        ]);

        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return $this->curlResponse($response, $status);
    }

    private function isTokenExpired(): bool
    {
        return (time() - $this->tokenData['date']) > $this->tokenData['expires_in'];
    }

    private function refreshAccessToken()
    {
        $tokenRefreshData = [
            'client_id' => $this->config['client_id'],
            'refresh_token' => $this->tokenData['refresh_token'],
            'grant_type' => 'refresh_token'
        ];

        $response = $this->queryAccessToken($tokenRefreshData);

        if ($response['status'] == 200) {
            $response['data']['date'] = time();

            $this->saveDataToken($response['data']);
            $this->tokenData = $response['data'];
        }
    }

    private function saveDataToken(array $data): void
    {
        file_put_contents('storage/' . $this->config['client_id'] . '.json', json_encode($data));
    }

    private function getDataToken()
    {
        $fileName = 'storage/' . $this->config['client_id'] . '.json';

        if (file_exists($fileName)) {
            $fileContent = file_get_contents($fileName);
            $this->tokenData = json_decode($fileContent, true);
        } else {
            throw new Exception('Файл не существует');
        }
    }

    private function curlResponse($data, $status): array
    {
        $data = json_decode($data, true);

        if ($status < 200 || $status >= 300) {
            $errorDetail = isset($data['detail']) ? $data['detail'] : 'Отсутствует описание ошибки';
            throw new Exception('Ошибка запроса: {код: ' . $status . ', описание: ' . $errorDetail . '} ');

        }
        return [
            "data" => $data,
            "status" => $status
        ];
    }
}

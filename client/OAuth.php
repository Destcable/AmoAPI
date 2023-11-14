<?php

namespace Client;

class OAuth
{ 
    private string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function queryAccessToken(array $data)
    { 
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_URL => $this->url . 'oauth2/access_token',
            CURLOPT_POSTFIELDS => json_encode($data),
        ]);

        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return [ 'data' => json_decode($response), 'status' => $status ];
    }
}
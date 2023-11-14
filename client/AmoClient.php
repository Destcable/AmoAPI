<?php

namespace Client;

use Api\App\Utils\Storage;
use Client\OAuth;
use Client\Services\ContactService;
use Client\Services\LeadService;

class AmoClient 
{ 
    private array $config = []; 

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function firstAuth(string $code)
    {
        $auth = new OAuth($this->config['url']);

        $authData = [
            'client_secret' => $this->config['client_secret'],
            'client_id' => $this->config['client_id'],
            'redirect_uri' => $this->config['redirect_uri'],
            'code' => $code,
            'grant_type' => 'authorization_code'
        ];

        return $auth->queryAccessToken($authData);
    }

    public function contacts()
    {
        return new ContactService($this);
    }

    public function leads()
    {
        return new LeadService($this);
    }

    public function postQuery(string $path, array $data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://artmobpavlov21.amocrm.ru/api/v4/companies',
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImE3YWE3MGY5NmExZmRiYjdkNGViZmZjNjA2MTFkMDkzZmQ3Y2Y1OGY4ZjljMTgyN2FkZTc1Nzg2YWJhYmE5MGI2YWJmNzBiMTBlNWNkNTJiIn0.eyJhdWQiOiI4NWM2MDQwNC1lY2FhLTQ0NmYtYmYzYi1mYjkzNzA3MmEzYjEiLCJqdGkiOiJhN2FhNzBmOTZhMWZkYmI3ZDRlYmZmYzYwNjExZDA5M2ZkN2NmNThmOGY5YzE4MjdhZGU3NTc4NmFiYWJhOTBiNmFiZjcwYjEwZTVjZDUyYiIsImlhdCI6MTY5OTk5NjU0NywibmJmIjoxNjk5OTk2NTQ3LCJleHAiOjE3MDAwODI5NDcsInN1YiI6IjEwMzM0NzAyIiwiZ3JhbnRfdHlwZSI6IiIsImFjY291bnRfaWQiOjMxNDA0OTI2LCJiYXNlX2RvbWFpbiI6ImFtb2NybS5ydSIsInZlcnNpb24iOjIsInNjb3BlcyI6WyJwdXNoX25vdGlmaWNhdGlvbnMiLCJmaWxlcyIsImNybSIsImZpbGVzX2RlbGV0ZSIsIm5vdGlmaWNhdGlvbnMiXX0.AzWW2zfdWlhEIltRGdgcC3bObxg-izDo5oebBmrKdmsniOrxdwKxwEZRQuCJfkTPkzO29JqeogCU9YA80kAgD_s3oV3vbdZRSA43FUx3qCKZxXnG7zcuw1zk9VzgOKw0ZdWv2KWxfwENvnZ54Y0jKIybI0Tb_HJ4KbrwsCNr8MfP7jsQmnwnTjkV5t0OC2O_oiyUpxCozB0hdFqBbqHLUPI-Ggz1gv6Mlef5_nb3Q7RLY0BHM9OZQOac0_vG-pmF369vQoS5585k0WQSdd2WOAa5kSJYPx5BLvZcV-jMvPekqX1-rtmpH9Rc2TJdJFqGNq6Pz6LZIsTW7CKYqK7TGQ',
                'Content-Type: application/json'
            ],
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([ 
                "name" => "Павлов Артем Ренатович"
            ]),
        ]);
        
        $response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        var_dump($response);
        curl_close($curl);

        return [ ];
    }

    private function getAccessToken()
    {
        return Storage::get('storage/'.$this->config['client_id'] . '.json');
    }
}
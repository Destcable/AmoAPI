<?php
namespace Api\App\Controllers;

use Api\App\Utils\Response;
use Api\App\Utils\Storage;
use Client\AmoClient;
use Exception;

class AuthController
{
    public function firstAuth()
    {
        if (empty($_POST['code'])) {
            throw new Exception("Отсутствует параметр code");
        }
        $config = include('api/app/config.php');

        $client = new AmoClient($config);
        
        $response = $client->firstAuth($_POST['code']);

        if ($response['status'] === 200) { 
            Storage::create('storage/'. $config['client_id'] . '.json', $response['data']);
        };

        return Response::success($response, $response['status']);
    }
}
<?php

namespace Api\App\Controllers;

use Api\App\Utils\Response;
use Client\AmoClient;

class AmoController
{
    public function create()
    {
        $client = new AmoClient(include('api/app/config.php'));

        var_dump($client->contacts()->create());

        // Response::success(["data" => 123]);
    }
}
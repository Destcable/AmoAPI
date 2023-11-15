<?php

namespace Api\App\Controllers;

use Api\App\Services\CreateContact;
use Api\App\Services\CreateLead;
use Api\App\Utils\Response;
use Client\AmoClient;

class AmoController
{
    public function create()
    {
        $client = new AmoClient(include('api/app/config.php'));
        CreateContact::handle($client, $_POST);
        // CreateLead::handle($client, $_POST);
        // Response::success(["data" => 123]);
    }
}
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
        $contact = CreateContact::handle($client, $_POST);
        $contactID = $contact['data']['_embedded']['contacts'][0]['id'];

        $response = CreateLead::handle($client, $_POST, $contactID);

        Response::success($response, $response['status']);
    }
}
<?php

namespace Api\App\Controllers;

use Api\App\Utils\Response;
use Client\AmoClient;

class AmoController
{
    public function create()
    {
        $request = $_POST;
        $client = new AmoClient(include('api/app/config.php'));
        if ( $request['price'] ) { 

        }
        $contact = $client->leads()->create();
        $contact->name  = 'Artem';
        $contact->price = 12313231; 
        $contact->save();

        // Response::success(["data" => 123]);
    }
}
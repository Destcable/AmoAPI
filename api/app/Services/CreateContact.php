<?php

namespace Api\App\Services;

use Client\AmoClient;
use Exception;

class CreateContact
{ 
    public static function handle(AmoClient $client,  $request)
    {
        if (isset($request['name']) && isset($request['price'])) { 
            $contact = $client->contacts()->create();
            $contact->name  = 'Artem2';
            return $contact->save();
        }

        throw new Exception("Заполните обязательные поля {name, phone, email}");
    }
}
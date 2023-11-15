<?php

namespace Api\App\Services;

use Client\AmoClient;
use Exception;

class CreateLead
{
    public static function handle(AmoClient $client,  $request)
    {
        if (isset($request['name']) && isset($request['price'])) { 
            $lead = $client->leads()->create();
            $lead->name  = 'Artem';
            $lead->price = 12313231; 
            $lead->contact_id = 11371419;
            return $lead->save();
        }

        throw new Exception("Заполните обязательные поля {price}");
    }
}
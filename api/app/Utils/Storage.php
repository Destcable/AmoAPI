<?php

namespace Api\App\Utils;

class Storage
{ 
    public static function create(string $path, $data)
    {
        file_put_contents($path, json_encode($data));
    }

    public static function get(string $path)
    {
        file_get_contents($path);
        
    }
}
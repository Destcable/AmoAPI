<?php

namespace Api\App\Utils;

class Response
{ 
    public static function success($data, int $code = 200)
    {
        header('Content-Type: json/application'); 
        http_response_code($code);
        echo json_encode($data);
    }
}
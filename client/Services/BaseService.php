<?php

namespace Client\Services;

use Client\AmoClient;

abstract class BaseService
{
    private AmoClient $amoClient;
    protected string $entity;

    public function __construct(AmoClient $client)
    {
        $this->amoClient = $client;    
    }
    protected $model;

    public function create()
    {
        $this->amoClient->postQuery($this->entity, ['name' => 'string']);
    }
}
<?php

namespace Client\Services;

use Client\AmoClient;

class BaseService
{
    public int $id;
    public string $entity;
    protected $model;
    protected AmoClient $client;

    public function __construct(AmoClient $client)
    {
        $this->client = $client;
    }

    public function __set($name, $value)
    {
        return $this->model->$name = $value;
    }

    public function __get($name)
    {
        return $this->model->$name;
    }

    public function create()
    {
        $this->model = new $this->model();
        return $this;
    }

    public function save()
    {
        $this->model->getModifiedFields()['_embedded']['contacts']['id'] =11371419;
        $modifiedFields = $this->model->getModifiedFields();

        var_dump($modifiedFields);

        $data = [
        'name' => 'Заявка с сайта от ' . $modifiedFields['name'],
        'price' => $modifiedFields['price'],
        '_embedded' => [
            'contacts' => [
                [
                    'id' => 11371419,
                ]
            ]
        ]];

        $this->checkRequiredFields($modifiedFields);

        return $this->client->postQuery($this->createRequestURL($this->entity), [$modifiedFields]);
    }

    private function createRequestURL(string $path, int $id = null)
    {
        return $path . ($id ? "/$id" : '');
    }


    protected function checkRequiredFields(array $data)
    {
        $diff = array_diff($this->model->getRequiredFields(), array_keys($data));

        if (!empty($diff)) {
            throw new \Exception('Отсутствуют обязательные свойства: ' . implode(',', $diff));
        }
    }
}

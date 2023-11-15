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
        $modifiedFields = $this->model->getModifiedFields();
        if ($this->entity === "contacts") {
            $modifiedFields = [
                'name' => 'Имя контакта',
                'custom_fields' => [
                    [
                        "field_id" => 3,
                        "field_name" => "Телефон",
                        "field_code" => "PHONE",
                        "field_type" => "multitext",
                        "values"=> [
                            [
                                "value" => "+79123",
                                "enum_id" => 1,
                                "enum" => "WORK"
                            ]
                        ]
                    ],
                ],
            ];
            // $modifiedFields['custom_fields_values'][0]['field_code'] = "PHONE";
            // $modifiedFields['custom_fields_values'][0]['field_code'][0]['values'][0]['value'] = "+79123";
        }

        // if (!empty($modifiedFields['contact_id'])) {
        //     $modifiedFields['_embedded']['contacts'][0]['id'] = $modifiedFields['contact_id'];
        // }

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

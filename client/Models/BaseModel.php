<?php

namespace Client\Models;

abstract class BaseModel
{
    protected array $modifiedFields = [];
    protected array $requiredFields = [];
    protected array $writableFields = [];

    public function __set($key, $value)
    {
        if (in_array($key, $this->writableFields) || in_array($key, $this->requiredFields)) {
            $this->modifiedFields[$key] = $value;
        } else {
            throw new \Exception('Свойство ' . $key . ' не разрешено.');
        }
    }

    public function __get($key)
    {
        return $this->modifiedFields[$key] ?? $this->data[$key] ?? null;
    }

    public function getModifiedFields(): array
    {
        return $this->modifiedFields;
    }

    public function getRequiredFields(): array
    {
        return $this->requiredFields;
    }
}

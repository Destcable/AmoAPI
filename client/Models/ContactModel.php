<?php

namespace Client\Models;

use Client\Models\BaseModel;

class ContactModel extends BaseModel
{
    protected array $writableFields = [
        'id',
        'first_name'
    ];

    protected array $requiredFields = [
        'name'
    ];
}

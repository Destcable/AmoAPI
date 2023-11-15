<?php

namespace Client\Models;

use Client\Models\BaseModel;

class LeadModel extends BaseModel
{
    protected array $writableFields = [
        'id',
        'status_id',
        'price',
        'contact_id'
    ];

    protected array $requiredFields = [
        'name',
    ];
}
<?php

namespace Client\Services;

use Client\Services\BaseService;
use Client\Models\ContactModel;
use Client\Traits\NoteTrait;

class ContactService extends BaseService
{
    public string $entity = 'contacts';
    protected $model = ContactModel::class;
}

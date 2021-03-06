<?php

namespace Api\Resources\Contact;

use Api\Resources\BaseResource;
use Api\Support\ContactTypes;

class ExactSearchResource extends BaseResource
{
    public $validation = [
        'type' => 'required|enum:' . ContactTypes::class,
        'identifier' => 'string|required',
    ];
}

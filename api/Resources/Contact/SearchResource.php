<?php

namespace Api\Resources\Contact;

use Api\Resources\BaseResource;

class SearchResource extends BaseResource
{
    public $validation = [
        'filters.subscription_type' => 'exists:subscription_types,name',
    ];
}

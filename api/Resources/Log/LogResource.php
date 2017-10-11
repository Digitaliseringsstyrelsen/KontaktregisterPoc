<?php

namespace Api\Resources\Log;

use Api\Resources\BaseResource;

class LogResource extends BaseResource
{
    public $validation = [
        'start' => 'date',
        'end'   => 'date',
        'type'  => 'string',
    ];
}

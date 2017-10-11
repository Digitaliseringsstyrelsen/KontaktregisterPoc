<?php

namespace Api\Resources\Media;

use Api\Resources\BaseResource;
use Api\Validation\MediaValidationRule;

class MediaResource extends BaseResource
{
    public $validation = [
        'type' => 'required',
        'data' => 'validation_rules:' . MediaValidationRule::class,
    ];
}

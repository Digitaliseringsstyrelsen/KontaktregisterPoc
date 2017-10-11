<?php

namespace Api\Resources\Contact;

use Api\Resources\BaseResource;
use Api\Resources\Media\MediaResource;

class RegisterResource extends BaseResource
{
    public $validation = [
        'contact'      => 'required|resource:' . ContactResource::class,
        'subscription' => 'required|resource:' . SubscriptionResource::class,
        'media'        => 'required|resource:' . MediaResource::class,
    ];
}

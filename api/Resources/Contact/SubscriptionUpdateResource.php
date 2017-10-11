<?php

namespace Api\Resources\Contact;

use Api\Resources\BaseResource;

class SubscriptionUpdateResource extends BaseResource
{
    public $validation = [
        'end_at' => 'date',
        'source_contact_id' => 'int|contact,id'
    ];
}

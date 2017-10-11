<?php

namespace Api\Resources\Contact;

use Api\Resources\BaseResource;

class SubscriptionResource extends BaseResource
{
    public $validation = [
        'subscription_type_id' => 'required|exists:subscription_types,id',
        'source_contact_id'    => 'numeric|nullable|min:1',
        'start_at'             => 'date',
        'end_at'               => 'date',
    ];
}

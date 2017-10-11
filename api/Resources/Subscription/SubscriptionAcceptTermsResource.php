<?php

namespace Api\Resources\Subscription;

use Api\Resources\BaseResource;

class SubscriptionAcceptTermsResource extends BaseResource
{
    public $validation = [
        'term_id' => 'required|exists:terms,id',
    ];
}

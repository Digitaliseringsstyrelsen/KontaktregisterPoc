<?php

namespace Api\Resources\MediaSubscription;

use Api\Resources\BaseResource;

class MediaSubscriptionResource extends BaseResource
{
    public $validation = [
        'subscription_id' => 'required|exists:subscriptions,id',
        'media_id' => 'required|exists:media,id',
    ];
}

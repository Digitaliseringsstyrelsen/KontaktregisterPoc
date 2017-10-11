<?php

namespace Api\Maps;

use Api\Features\SubscriptionTypes\Index;
use Api\Resources\SubscriptionType\SubscriptionTypeResource;

class SubscriptionTypeMap extends MapAbstract
{
    public $map = [
        Index::class => SubscriptionTypeResource::class,
    ];
}

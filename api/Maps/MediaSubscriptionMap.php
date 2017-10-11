<?php

namespace Api\Maps;

use Api\Features\MediaSubscription\Create;
use Api\Features\MediaSubscription\Destroy;
use Api\Resources\MediaSubscription\MediaSubscriptionResource;

class MediaSubscriptionMap extends MapAbstract
{
    public $map = [
        Create::class => MediaSubscriptionResource::class,
        Destroy::class => MediaSubscriptionResource::class,
    ];
}

<?php

namespace Api\Maps;

use Api\Features\Subscriptions\AcceptTerms;
use Api\Features\Subscriptions\Create;
use Api\Features\Subscriptions\Destroy;
use Api\Features\Subscriptions\Get;
use Api\Features\Subscriptions\Index;
use Api\Features\Subscriptions\Update;
use Api\Resources\Contact\SubscriptionResource;
use Api\Resources\Contact\SubscriptionUpdateResource;
use Api\Resources\Subscription\SubscriptionAcceptTermsResource;

class SubscriptionMap extends MapAbstract
{
    public $map = [
        Index::class => SubscriptionResource::class,
        Get::class   => SubscriptionResource::class,
        Create::class => SubscriptionResource::class,
        Update::class => SubscriptionUpdateResource::class,
        Destroy::class => SubscriptionResource::class,
        AcceptTerms::class => SubscriptionAcceptTermsResource::class,
    ];
}

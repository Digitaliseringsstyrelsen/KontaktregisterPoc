<?php

namespace Api\Features\SubscriptionTypes;

use Api\Features\Feature;
use Api\Models\SubscriptionType;
use Api\Transformers\SubscriptionTypesTransformer;

/**
 * @SWG\Definition(definition="subscription-type")
 */
class Index extends Feature
{
    protected $transformer = SubscriptionTypesTransformer::class;

    protected function handle()
    {
        return SubscriptionType::all();
    }
}

<?php

namespace Api\Features\Subscriptions;

use Api\Features\Feature;
use Api\Transformers\SubscriptionTransformer;

/**
 * @SWG\Definition(definition="subscription")
 */
class Index extends Feature
{
    protected $transformer = SubscriptionTransformer::class;

    /**
     * @SWG\Property(ref="#definitions/Contact")
     */
    public $contact;

    protected function handle()
    {
        return $this->contact->subscriptions;
    }
}

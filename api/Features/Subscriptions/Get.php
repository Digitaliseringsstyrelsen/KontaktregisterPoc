<?php

namespace Api\Features\Subscriptions;

use Api\Exceptions\NotFoundException;
use Api\Features\Feature;
use Api\Models\Subscription;
use Api\Transformers\SubscriptionTransformer;

/**
 * @SWG\Definition(definition="subscription-get")
 */
class Get extends Feature
{
    protected $transformer = SubscriptionTransformer::class;

    /**
     * @SWG\Property(ref="#definitions/Contact")
     */
    public $contact;

    /**
     * Subscription resource.
     * @var Subscription
     * @SWG\Property()
     */
    public $subscription;

    protected function handle()
    {
        if (! $subscription = $this->contact->subscriptions()->whereId($this->subscription->id)->first()) {
            throw new NotFoundException('Subscription not found');
        }

        return $subscription;
    }
}

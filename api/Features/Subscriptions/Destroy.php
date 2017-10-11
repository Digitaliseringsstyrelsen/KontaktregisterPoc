<?php

namespace Api\Features\Subscriptions;

use Api\Features\Feature;
use Api\Models\Contact;
use Api\Models\Subscription;
use Api\Transformers\SubscriptionTransformer;
use RuntimeException;

/**
 * @SWG\Definition(definition="subscription-destroy")
 */
class Destroy extends Feature
{
    protected $transformer = SubscriptionTransformer::class;

    /**
     * @SWG\Property(ref="#definitions/Contact")
     */
    public $contact;

    /**
     * @SWG\Property(ref="#definitions/Subscription")
     */
    public $subscription;

    protected function handle()
    {
        if (! $this->subscription->delete()) {
            throw new RuntimeException(sprintf('Error deleting subscription %s for contact id: %s', $this->subscription->id, $this->contact->identifier));
        }
    }
}

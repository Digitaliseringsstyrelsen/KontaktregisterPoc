<?php

namespace Api\Features\Subscriptions;

use Api\Features\Feature;
use Api\Models\Subscription;
use Api\Models\Term;
use Api\Transformers\SubscriptionTransformer;

/**
 * @SWG\Definition(definition="subscription-accept-terms")
 */
class AcceptTerms extends Feature
{
    protected $transformer = SubscriptionTransformer::class;

    /**
     * @var Subscription $subscription
     * @SWG\Property(ref="#definitions/Subscription")
     */
    public $subscription;

    /**
     * Term id.
     * @var int
     * @SWG\Property()
     */
    public $term_id;

    protected function handle()
    {
        return tap($this->subscription, function (Subscription $subscription) {
            return $subscription->acceptTerms(Term::find($this->term_id));
        });
    }
}

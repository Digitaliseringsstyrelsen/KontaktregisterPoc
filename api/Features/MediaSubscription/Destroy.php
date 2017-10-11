<?php

namespace Api\Features\MediaSubscription;

use Api\Exceptions\NotFoundException;
use Api\Features\Feature;
use Api\Transformers\SubscriptionTransformer;

/**
 * @SWG\Definition(definition="media-subscription-destroy")
 */
class Destroy extends Feature
{
    protected $transformer = SubscriptionTransformer::class;


    /**
     * @SWG\Property(ref="#definitions/Contact")
     */
    public $contact;

    /**
     * Subscription id.
     * @var int
     * @SWG\Property()
     */
    public $subscription_id;

    /**
     * Media id.
     * @var int
     * @SWG\Property()
     */
    public $media_id;

    protected function handle()
    {
        if (! $subscription = $this->contact->subscriptions()->whereId($this->subscription_id)->first()) {
            throw new NotFoundException('Subscription not found or does not belong to the specified contact');
        }

        if (! $subscription->medias()->whereMediaId($this->media_id)->count()) {
            throw new NotFoundException(sprintf('Media %s is not associated with subscription %s', $this->media_id, $this->subscription_id));
        }

        $subscription->medias()->detach([$this->media_id]);
    }
}

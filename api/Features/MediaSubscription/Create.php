<?php

namespace Api\Features\MediaSubscription;

use Api\Exceptions\NotFoundException;
use Api\Features\Feature;
use Api\Transformers\SubscriptionTransformer;
use Carbon\Carbon;

/**
 * @SWG\Definition(definition="media-subscription-create")
 */
class Create extends Feature
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

        if (! $media = $this->contact->medias()->whereId($this->media_id)->first()) {
            throw new NotFoundException('Media not found or does not belong to the specified contact');
        }

        return tap($subscription, function ($subscription) use ($media) {
            if ($subscription->medias()->whereMediaId($media->id)->exists()) {
                return;
            }
            $subscription->medias()->attach($media->id, ['subscribed_at' => Carbon::now()]);
        });
    }
}

<?php

namespace Api\Features\Subscriptions;

use Api\Exceptions\BadRequestException;
use Api\Features\Feature;
use Api\Models\Contact;
use Api\Models\SubscriptionType;
use Api\Transformers\SubscriptionTransformer;
use Carbon\Carbon;

/**
 * @SWG\Definition(definition="subscription-create")
 */
class Create extends Feature
{
    protected $transformer = SubscriptionTransformer::class;

    /**
     * @SWG\Property(ref="#definitions/Contact")
     */
    public $contact;

    /**
     * Subscription type.
     * @var int
     * @SWG\Property()
     */
    public $subscription_type_id;

    /**
     * Source contact id.
     * @var int
     * @SWG\Property()
     */
    public $source_contact_id;

    /**
     * When the subscription should start.
     * @var Carbon
     * @SWG\Property()
     */
    public $start_at;

    /**
     * When the subscription should end.
     * @var Carbon
     * @SWG\Property()
     */
    public $end_at;

    protected function handle()
    {
        if (! $subscriptionType = SubscriptionType::find($this->subscription_type_id)){
            throw new BadRequestException('Subscription type not found');
        }

        $start_at = $this->start_at ? Carbon::parse($this->start_at) : Carbon::today();
        $source = $this->source_contact_id ? Contact::whereIdentifier($this->source_contact_id)->first()->id : $this->contact->id;

        if ($this->contact->subscriptions()->where('subscription_type_id', $subscriptionType->id)->where('source_contact_id', $source)->whereRaw('"' . $start_at . '" between started_at and ended_at')->exists()) {
            throw new BadRequestException('Start date overlaps with an existing subscription');
        }

        $subscription = $this->contact
            ->subscriptions()
            ->firstOrCreate([
                'subscription_type_id' => $subscriptionType->id,
                'source_contact_id' => $source,
            ]);

        $subscription->update([
            'started_at' => $start_at,
            'ended_at' => $this->end_at ? Carbon::parse($this->end_at) : null
        ]);

        return $subscription;
    }
}

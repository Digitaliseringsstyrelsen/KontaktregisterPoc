<?php

namespace Api\Features\Subscriptions;

use Api\Exceptions\BadRequestException;
use Api\Exceptions\NotFoundException;
use Api\Features\Feature;
use Api\Models\Contact;
use Api\Transformers\SubscriptionTransformer;
use Carbon\Carbon;

/**
 * @SWG\Definition(definition="subscription-update")
 */
class Update extends Feature
{
    protected $transformer = SubscriptionTransformer::class;

    /**
     * @SWG\Property(ref="#definitions/Subscription")
     */
    public $subscription;

    /**
     * When the subscription should end.
     * @var Carbon
     * @SWG\Property()
     */
    public $end_at;

    /**
     * @SWG\Property(ref="#definitions/Contact")
     */
    public $source_contact_id;

    protected function handle()
    {
        $this->subscription->ended_at = $this->end_at ? Carbon::parse($this->end_at) : null;

        if ($this->source_contact_id) {
            $this->subscription->source_contact_id = $this->getSourceContact($this->source_contact_id)->id;
        }

        return $this->subscription;
    }

    private function getSourceContact(int $source_contact_id): Contact
    {
        if ($this->subscription->owner_contact_id == $source_contact_id) {
            throw new BadRequestException('You can not set source to the same contact.');
        }

        if (! $contact = Contact::find($source_contact_id)) {
            throw new NotFoundException();
        }

        return $contact;
    }
}

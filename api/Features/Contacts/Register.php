<?php

namespace Api\Features\Contacts;

use Api\Features\Feature;
use Api\Models\Contact;
use Api\Transformers\ContactTransformer;
use Carbon\Carbon;
use DB;

/**
 * @SWG\Definition(definition="contact-register")
 */
class Register extends Feature
{
    protected $transformer = ContactTransformer::class;

    /**
     * @SWG\Property(ref="#definitions/Contact")
     */
    public $contact;

    /**
     * @SWG\Property(ref="#definitions/Media")
     */
    public $media;

    /**
     * @SWG\Property(ref="#definitions/Subscription")
     */
    public $subscription;

    protected function handle()
    {
        return DB::transaction(function () {
            /** @var Contact $contact */
            $contact = Contact::create($this->contact);

            $subscription = $contact->subscriptions()->create([
                'subscription_type_id' => $this->subscription['subscription_type_id'],
                'source_contact_id'    => isset($this->contact['source_contact_id']) ? $this->contact['source_contact_id'] : $contact->id,
            ]);

            $media = $contact->medias()->create([
                'type' => $this->media['type'],
                'data' => $this->media['data'],
            ]);

            $subscription->medias()->attach($media->id, ['subscribed_at' => Carbon::now()]);

            return $contact->fresh();
        });
    }
}

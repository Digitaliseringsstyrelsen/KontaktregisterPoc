<?php

namespace Api\Transformers;

use Api\Models\Contact;

class ContactTransformer extends Transformer
{

    protected $defaultIncludes = [
        'medias',
        'subscriptions',
    ];

    public function transform(Contact $contact)
    {
        return [
            'type' => $contact->type,
            'identifier' => $contact->identifier,
        ];
    }

    public function includeMedias(Contact $contact)
    {
        return $this->collection($contact->medias, new MediaTransformer());
    }

    public function includeSubscriptions(Contact $contact)
    {
        return $this->collection($contact->subscriptions, new SubscriptionTransformer());
    }
}

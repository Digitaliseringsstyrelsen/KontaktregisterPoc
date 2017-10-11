<?php

namespace Api\Transformers;

use Api\Models\Subscription;

class SubscriptionTransformer extends Transformer
{
    protected $defaultIncludes = [
        'medias',
        'accepted_terms'
    ];

    public function transform(Subscription $subscription)
    {
        return [
            'id' => $subscription->id,
            'type' => $subscription->type->name,
            'started_at' => $subscription->started_at->toDateString(),
            'ended_at' => $subscription->ended_at ? $subscription->ended_at->toDateString() : null,
        ] + ($subscription->owner_contact_id !== $subscription->source_contact_id ?  [
                'source_contact' => $this->transformRelation(SimpleContactTransformer::class, $subscription->source),
            ] : []);
    }

    public function includeMedias(Subscription $subscription)
    {
        return $this->collection($subscription->medias, new MediaTransformer());
    }

    public function includeAcceptedTerms(Subscription $subscription)
    {
        return $this->item($subscription->getLatestAcceptedTerm(), new AcceptedTermTransformer());
    }
}

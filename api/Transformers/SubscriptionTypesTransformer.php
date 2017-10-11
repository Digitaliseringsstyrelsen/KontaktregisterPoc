<?php

namespace Api\Transformers;

use Api\Models\SubscriptionType;

class SubscriptionTypesTransformer extends Transformer
{
    public function transform(SubscriptionType $subscriptionType)
    {
        return [
            'id'   => $subscriptionType->id,
            'name' => $subscriptionType->name,
            'terms' => $this->transformCollection(TermTransformer::class, $subscriptionType->terms()->latest('version')->get()),
        ];
    }
}

<?php

namespace Api\Transformers;

use Api\Models\SubscriptionType;

class SubscriptionTypeTransformer extends Transformer
{
    public function transform(SubscriptionType $subscriptionType)
    {
        return [
            'id'   => $subscriptionType->id,
            'name' => $subscriptionType->name,
        ];
    }
}

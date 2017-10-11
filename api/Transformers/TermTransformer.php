<?php

namespace Api\Transformers;

use Api\Models\Term;

class TermTransformer extends Transformer
{
    public function transform(Term $term)
    {
        return [
            'id'                => $term->id,
            'version'           => $term->version,
            'text'              => $term->text,
            'subscription_type' => $this->transformRelation(SubscriptionTypeTransformer::class, $term->subscriptionType),
        ];
    }
}

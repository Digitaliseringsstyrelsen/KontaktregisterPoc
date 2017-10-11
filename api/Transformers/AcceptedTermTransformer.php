<?php

namespace Api\Transformers;

use Api\Models\AcceptedTerm;

class AcceptedTermTransformer extends Transformer
{
    public function transform(AcceptedTerm $term)
    {
        return [
            'id' => $term->terms->id,
            'version' => $term->terms->version,
            'latest' => $term->terms->isLatestVersion(),
            'accepted_at' => $term->accepted_at->toDateTimeString(),
        ];
    }
}

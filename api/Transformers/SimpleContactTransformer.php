<?php

namespace Api\Transformers;

use Api\Models\Contact;

class SimpleContactTransformer extends Transformer
{
    public function transform(Contact $contact)
    {
        return [
            'type' => $contact->type,
            'identifier' => $contact->identifier,
        ];
    }
}

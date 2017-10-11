<?php

namespace Api\Features\Contacts;

use Api\Features\Feature;
use Api\Models\Contact;
use Api\Transformers\ContactTransformer;

/**
 * @SWG\Definition(definition="contact-create")
 */
class Create extends Feature
{
    protected $transformer = ContactTransformer::class;

    /**
     * Contact type, company or person.
     * @var string
     * @SWG\Property()
     */
    public $type;

    /**
     * Contact unique identifier, their cvr or cpr number.
     * @var string
     * @SWG\Property()
     */
    public $identifier;

    protected function handle()
    {
        return Contact::create([
            'type' => $this->type,
            'identifier' => $this->identifier,
        ]);
    }
}

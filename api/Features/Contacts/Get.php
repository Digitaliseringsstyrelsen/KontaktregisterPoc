<?php

namespace Api\Features\Contacts;

use Api\Features\Feature;
use Api\Models\Contact;
use Api\Transformers\ContactTransformer;

/**
 * @SWG\Definition(definition="contact")
 */
class Get extends Feature
{
    protected $transformer = ContactTransformer::class;

    /**
     * Contact model.
     * @var Contact
     * @SWG\Property()
     */
    public $contact;

    protected function handle()
    {
        return $this->contact;
    }
}

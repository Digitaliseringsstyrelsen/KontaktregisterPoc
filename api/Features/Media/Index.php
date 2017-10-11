<?php

namespace Api\Features\Media;

use Api\Features\Feature;
use Api\Transformers\MediaTransformer;

/**
 * @SWG\Definition(definition="media")
 */
class Index extends Feature
{
    protected $transformer = MediaTransformer::class;

    /**
     * @SWG\Property(ref="#definitions/Contact")
     */
    public $contact;

    protected function handle()
    {
        return $this->contact->medias;
    }
}

<?php

namespace Api\Features\Terms;

use Api\Features\Feature;
use Api\Models\Term;
use Api\Transformers\TermTransformer;

/**
 * @SWG\Definition(definition="terms-get")
 */
class Get extends Feature
{
    protected $transformer = TermTransformer::class;

    /**
     * Term.
     * @var Term
     * @SWG\Property()
     */
    public $term;

    protected function handle()
    {
        return $this->term;
    }
}

<?php

namespace Api\Features\Terms;

use Api\Features\Feature;
use Api\Models\Term;
use Api\Transformers\TermTransformer;

class Index extends Feature
{
    protected $transformer = TermTransformer::class;

    protected function handle()
    {
        return Term::orderByVersion()->get();
    }
}

<?php

namespace Api\Maps;

use Api\Features\Terms\Get;
use Api\Features\Terms\Index;
use Api\Resources\Term\TermResource;

class TermMap extends MapAbstract
{
    public $map = [
        Index::class => TermResource::class,
        Get::class   => TermResource::class,
    ];
}

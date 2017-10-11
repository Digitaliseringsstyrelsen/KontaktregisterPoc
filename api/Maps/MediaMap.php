<?php

namespace Api\Maps;

use Api\Features\Media\Create;
use Api\Features\Media\Destroy;
use Api\Features\Media\Index;
use Api\Features\Media\Update;
use Api\Resources\Media\MediaResource;

class MediaMap extends MapAbstract
{
    public $map = [
        Index::class   => MediaResource::class,
        Create::class  => MediaResource::class,
        Destroy::class => MediaResource::class,
        Update::class  => MediaResource::class,
    ];
}

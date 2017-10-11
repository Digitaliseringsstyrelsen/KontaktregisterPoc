<?php

namespace Api\Maps;


use Api\Features\Contacts\Create;
use Api\Features\Contacts\ExactSearch;
use Api\Features\Contacts\Get;
use Api\Features\Contacts\Log;
use Api\Features\Contacts\Register;
use Api\Features\Contacts\Search;
use Api\Resources\Contact\ContactResource;
use Api\Resources\Contact\ExactSearchResource;
use Api\Resources\Log\LogResource;
use Api\Resources\Contact\RegisterResource;
use Api\Resources\Contact\SearchResource;

class ContactMap extends MapAbstract
{
    public $map = [
        Get::class         => ContactResource::class,
        Create::class      => ContactResource::class,
        Search::class      => SearchResource::class,
        Register::class    => RegisterResource::class,
        Log::class         => LogResource::class,
    ];
}

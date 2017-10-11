<?php

namespace Digist\Services\Log;

use Api\Models\Contact;
use Digist\Services\Log\Contracts\LoggerContract as Logger;

class Service
{
    protected $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function log(Contact $contact, $token, $type, $data)
    {
        return $this->logger->log($contact, $token, $type, $data);
    }
}

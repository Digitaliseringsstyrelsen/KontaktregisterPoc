<?php

namespace Digist\Services\Log\Providers;

use Api\Models\Contact;
use Digist\Jobs\AddLogEntry;
use Digist\Services\Log\Contracts\LoggerContract;

class QueueLogger implements LoggerContract
{
    public function log(Contact $contact, $token, $type, $data): bool
    {
        dispatch((new AddLogEntry($contact, $token, $type, (array) $data))->onQueue(config('logging.queue')));
        return true;
    }
}

<?php

namespace Digist\Services\Log\Contracts;

use Api\Models\Contact;

interface LoggerContract
{
    public function log(Contact $contact, $token, $type, $data): bool;
}

<?php

namespace Api\Contracts;

use Api\Models\Contact;

interface Loggable
{
    public function loggableEntity(): Contact;
}

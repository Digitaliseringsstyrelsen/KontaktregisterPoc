<?php

namespace Api\Exceptions;

class BadRequestException extends ApiException
{
    public function __construct($message = 'Request is malformed.', $code = 400)
    {
        parent::__construct($message, $code);
    }
}

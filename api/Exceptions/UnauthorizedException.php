<?php

namespace Api\Exceptions;

class UnauthorizedException extends ApiException
{
    public function __construct($message = 'Unauthorized', $code = 401)
    {
        parent::__construct($message, $code);
    }
}

<?php

namespace Api\Exceptions;


class UnauthenticatedException extends ApiException
{
    public function __construct($message = 'Authentication required.', $code = 401)
    {
        parent::__construct($message, $code);
    }
}

<?php

namespace Api\Exceptions;


class ForbiddenException extends ApiException
{
    public function __construct($message = 'Forbidden.', $code = 403)
    {
        parent::__construct($message, $code);
    }
}

<?php
namespace Api\Exceptions;


class NotFoundException extends ApiException
{
    public function __construct($message = 'Requested resource is not found', $code = 404)
    {
        parent::__construct($message, $code);
    }
}

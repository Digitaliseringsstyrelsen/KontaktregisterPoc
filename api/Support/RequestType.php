<?php

namespace Api\Support;

class RequestType extends Enums
{
    const __default = self::get;

    const get = 'request';
    const put = 'update';
    const post = 'create';
    const delete = 'delete';
}

<?php

namespace Api\Support;

class ContactTypes extends Enums
{
    const __default = self::PERSON;

    const PERSON = 'person';
    const COMPANY = 'company';
}

<?php

namespace Digist\Console\Commands;

trait ImportHelper
{
    protected $userTypes = [
        8 => 'person',
        10 => 'company',
    ];

    protected $originalTypes = [
        1 => 'email',
        2 => 'sms',
        3 => 'p_box',
    ];

    protected $media = [
        'email' => 'email',
        'sms' => 'number',
        'p_box' => 'p_box',
    ];

    public function getContactType(string $identifier) : string
    {
        return $this->userTypes[strlen($identifier)] ?? 'default';
    }

    public function hasDigitalPostSubscription($val) : bool
    {
        return (int) $val === 2;
    }

    public function getType($type) : string
    {
        return $this->originalTypes[$type];
    }

    public function buildMediaData($type, $data) : array
    {
        return [
            $this->media[$type] => $data,
        ];
    }
}

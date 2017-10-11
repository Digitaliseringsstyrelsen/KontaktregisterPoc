<?php

namespace Api\Support;

use Api\Exceptions\BadRequestException;
use Api\Models\Contact;

/**
 * This class is just temporal class, this idea needs to be extended
 * once we know how the types will be getting their respective limit
 * for each of the media types.
 *
 * Class Settings
 * @package Api\Support
 */
class Settings
{
    protected $mediaLimits = [
        'email' => 2,
        'sms'   => 1,
        'p_box' => 1,
        'address' => 2,
    ];

    public function getLimit(string $type) : int
    {
        if (! array_key_exists($type, $this->mediaLimits)) {
            throw new BadRequestException('Type key do not exist.');
        }

        return array_get($this->mediaLimits, $type);
    }

    public function canCreateMedia(string $type, Contact $contact) : bool
    {
        return $contact->medias()->where('type', $type)->count() < $this->getLimit($type);
    }
}

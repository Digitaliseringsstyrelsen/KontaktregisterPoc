<?php

namespace Api\Features\Media;

use Api\Exceptions\BadRequestException;
use Api\Features\Feature;
use Api\Transformers\MediaTransformer;
use Settings;

/**
 * @SWG\Definition(definition="media-create")
 */
class Create extends Feature
{
    protected $transformer = MediaTransformer::class;

    /**
     * @SWG\Property(ref="#definitions/Contact")
     */
    public $contact;

    /**
     * Media type.
     * @var string
     * @SWG\Property()
     */
    public $type;

    /**
     * Contact media data.
     * @var array
     * @SWG\Property()
     */
    public $data;

    protected function handle()
    {
        if (! Settings::canCreateMedia($this->type, $this->contact)) {
            throw new BadRequestException('Can not create more medias with that type');
        }

        return $this->contact->medias()->create([
            'type' => $this->type,
            'data' => $this->data,
        ]);
    }
}

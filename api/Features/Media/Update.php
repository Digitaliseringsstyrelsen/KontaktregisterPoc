<?php

namespace Api\Features\Media;

use Api\Exceptions\BadRequestException;
use Api\Features\Feature;
use Api\Transformers\MediaTransformer;

/**
 * @SWG\Definition(definition="media-update")
 */
class Update extends Feature
{
    /**
     * @SWG\Property(ref="#definitions/Contact")
     */
    public $contact;
    /**
     * @SWG\Property(ref="#definitions/Media")
     */
    public $media;
    /**
     * Media type.
     * @var string
     * @SWG\Property()
     */
    public $type;
    /**
     * Information data.
     * @var array
     * @SWG\Property()
     */
    public $data;
    protected $transformer = MediaTransformer::class;

    protected function handle()
    {
        if ($this->type !== $this->media->type) {
            throw new BadRequestException('Media type do not match.');
        }

        return tap($this->media, function () {
            return $this->media->data = $this->data;
        });
    }
}

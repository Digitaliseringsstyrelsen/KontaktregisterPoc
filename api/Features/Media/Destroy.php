<?php

namespace Api\Features\Media;

use Api\Exceptions\BadRequestException;
use Api\Features\Feature;
use Api\Models\Media;
use Api\Transformers\MediaTransformer;
use RuntimeException;

/**
 * @SWG\Definition(definition="media-destroy")
 */
class Destroy extends Feature
{
    protected $transformer = MediaTransformer::class;

    /**
     * @SWG\Property(ref="#definitions/Contact")
     */
    public $contact;

    /**
     * Media resource.
     * @var Media
     * @SWG\Property()
     */
    public $media;

    protected function handle()
    {
        if ($this->media->subscriptions()->count()) {
            throw new BadRequestException(sprintf('Media %s can not be deleted', $this->media->id));
        }

        if (! $this->media->delete()) {
            throw new RuntimeException(sprintf('Error deleting media %s', $this->media->id));
        }

        return;
    }
}

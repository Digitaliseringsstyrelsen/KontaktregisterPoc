<?php

namespace Api\Transformers;

use Api\Models\Media;

class MediaTransformer extends Transformer
{
    public function transform(Media $media)
    {
        $data = [
            'id' => $media->id,
            'type' => $media->type,
        ];

        if ($this->hasUnWashPermission()) {
            $data = array_merge($data, [
                'data' => $media->data,
            ]);
        }

        return $data;
    }
}

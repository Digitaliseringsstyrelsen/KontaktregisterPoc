<?php

namespace Api\Transformers;

use Digist\Models\ApiToken;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\TransformerAbstract;

abstract class Transformer extends TransformerAbstract
{
    public function transformRelation($transformer, $data)
    {
        $manager = new Manager();
        $manager->setSerializer(new ArraySerializer());

        return $manager->createData(new Item($data, new $transformer))->toArray();
    }

    public function transformCollection($transformer, Collection $collection)
    {
        return $collection->map(function ($item) use ($transformer) {
            return $this->transformRelation($transformer, $item);
        });
    }

    public function hasUnWashPermission() : bool
    {
        $apiToken = Auth::user();
        if ($apiToken && $apiToken instanceof ApiToken) {
            return $apiToken->doesRequiredWashData();
        }

        return true;
    }

    protected function item($data, $transformer, $resourceKey = null)
    {
        return is_null($data) ? $this->null() : parent::item($data, $transformer, $resourceKey);
    }

    protected function collection($data, $transformer, $resourceKey = null)
    {
        return empty($data) ? $this->null() : parent::collection($data, $transformer, $resourceKey);
    }
}

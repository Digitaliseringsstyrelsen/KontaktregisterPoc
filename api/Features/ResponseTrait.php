<?php

namespace Api\Features;

use Api\Support\CriteriaHelper;
use Api\Support\Paginator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\ArraySerializer;

trait ResponseTrait
{
    use CriteriaHelper;

    public function response($result)
    {
        if ($result instanceof Collection) {
            return $this->buildListResult($result);
        }

        if ($result instanceof Builder) {
            return $this->buildListResult($result->get()); // @codeCoverageIgnore
        }

        $transformedResult = $result ? $this->transform($result) : '';

        return response()->json($transformedResult, $this->getResponseStatusCode());
    }

    public function buildListResult($result)
    {
        /**
         * Washing and transformers generates a bottle neck
         * inside the application when the request contains
         * a collection that needs to handle a lot relation
         * transformations at the same time.
         * @var Collection $result
         */
        $attr = $result->map(function ($item) {
            return $this->transform($item);
        });

        /** @var LengthAwarePaginator $paginator */
        return new Paginator($attr, count($attr), request()->get('per_page', 100), request()->get('page'),
            $this->getFailed(), $this->getCriteria());
    }

    public function transform($response)
    {
        return fractal($response, new $this->transformer);
    }

    protected function getResponseStatusCode() : int
    {
        switch (request()->getMethod()) {
            case 'GET':
            case 'PUT':
                return 200;
            case 'POST':
                return 201;
            case 'DELETE':
                return 204;
            default:
                return 200;
        }
    }
}

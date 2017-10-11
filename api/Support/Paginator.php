<?php

namespace Api\Support;

use Illuminate\Pagination\LengthAwarePaginator;

class Paginator extends LengthAwarePaginator
{
    protected $failed;
    protected $criteria;

    public function __construct($items, $total, $perPage, $currentPage = null, $failed = [], $criteria = [])
    {
        $this->criteria = $criteria;
        $this->failed = $failed;

        parent::__construct($items, $total, $perPage, $currentPage, []);
    }

    public function toArray()
    {
        return [
            'total'         => $this->total(),
            'per_page'      => $this->perPage(),
            'current_page'  => $this->currentPage(),
            'last_page'     => $this->lastPage(),
            'next_page_url' => $this->nextPageUrl(),
            'prev_page_url' => $this->previousPageUrl(),
            'from'          => $this->firstItem(),
            'to'            => $this->lastItem(),
            'criteria'      => $this->criteria,
            'failed'        => $this->failed,
            'data'          => $this->items->toArray(),
        ];
    }
}

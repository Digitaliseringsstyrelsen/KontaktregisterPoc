<?php

namespace Api\Support;

trait CriteriaHelper
{
    /**
     * This contains all request filters, sort properties.
     * @var array
     */
    private $criteria = [];

    /**
     * This stores all the failed values from search.
     * @var array
     */
    private $failed = [];

    /**
     * Get failed attributes in query.
     * @return array
     */
    public function getFailed()
    {
        return $this->failed;
    }

    /**
     * Set fail attributes in query.
     * @param $val
     */
    public function setFailed($val)
    {
        if (is_null($val)) {
            return;
        }

        if (is_array($val)) {
            $this->failed[] = $val;

            return;
        }

        array_push($this->failed, $val);
    }

    /**
     * Get criteria for request.
     * @return array
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * Set criteria for request.
     * @param $val
     */
    public function setCriteria($val)
    {
        if (is_null($val)) {
            return;
        }

        if (is_array($val)) {
            $this->criteria = array_merge($this->criteria, $val);

            return;
        }

        array_push($this->criteria, $val);
    }
}

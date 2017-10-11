<?php

namespace Api\Features;

use Api\Contracts\Loggable;
use Api\Support\LogHelper;
use Digist\Services\Log\Service as Logger;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;

abstract class Feature
{
    use ResponseTrait, LogHelper;

    protected $transformer = null;

    protected $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;

        if (! $this->transformer) {
            throw new RuntimeException('Transformer is not defined'); // @codeCoverageIgnore
        }
    }

    abstract protected function handle();

    public function invoke()
    {
        /** @var Model $result */
        $result = $this->handle();
        if ($result instanceof Loggable) {
            $this->log($this->logger, $result, get_object_vars($this));
            $result->save();
        }

        return $this->response($result);
    }
}

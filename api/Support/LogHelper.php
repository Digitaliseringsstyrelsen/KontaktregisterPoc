<?php

namespace Api\Support;

use Auth;
use Digist\Models\ApiToken;
use Digist\Services\Log\Service as Logger;

trait LogHelper
{
    public function log(Logger $service, $result, $args) : void
    {
        switch (request()->method()) {
            case 'GET':
                $this->logGetMethod($service, $result);
                break;
            case 'POST':
            case 'PUT':
                $this->logInsertionMethod($service, $result, $this->cleanArguments($args));
                break;
            default:
                break;
        }
    }

    private function logGetMethod(Logger $service, $result) : void
    {
        $service->log($result->loggableEntity(), $this->getAuthor(), request()->method(), '');
    }

    private function logInsertionMethod(Logger $service, $result, $args) : void
    {
        $result->isDirty() ?
            $this->handleDirtyModel($service, $result, $args) :
            $this->handleCleanModel($service, $result, $args);
    }

    private function handleCleanModel(Logger $service, $result, $args)
    {
        foreach ($args as $key => $value) {
            if (is_array($value)) {
                foreach (array_keys($value) as $array_key) {
                    $service->log($result->loggableEntity(), $this->getAuthor(), request()->method(),
                        ['new_value' => $this->formatLogValue($array_key, $value[$array_key])]);
                }
                continue;
            }

            $service->log($result->loggableEntity(), $this->getAuthor(), request()->method(),
                ['new_value' => $this->formatLogValue($key, $value)]);
        }
    }

    private function handleDirtyModel(Logger $service, $result, $args)
    {
        foreach (array_filter($result->getDirty(), function ($dirty) use ($args) {
            return ! in_array($dirty, $args);
        }, ARRAY_FILTER_USE_KEY) as $key => $dirty) {
            $oldValue = $result->getOriginal($key);

            $loggable = [
                'new_value' => $this->formatLogValue($key, $dirty),
            ];

            if ($oldValue) {
                $loggable = array_merge($loggable, [
                    'old_value' => $this->formatLogValue($key, $oldValue),
                ]);
            }

            $service->log($result->loggableEntity(), $this->getAuthor(), request()->method(), $loggable);
        }
    }

    private function cleanArguments(array $args) : array
    {
        return array_filter($args, function ($val) {
            return ! in_array($val, [
                'logger',
                'criteria',
                'failed',
                'transformer',
                'contact',
                'subscription',
            ]);
        }, ARRAY_FILTER_USE_KEY);
    }

    public function formatLogValue($key, $val)
    {
        if (is_array($val)) {
            $val = implode(':', array_map(function ($mapValue) {
                if (is_array($mapValue)) {
                    return implode(':', array_values($mapValue));
                }

                return $mapValue;
            }, $val));
        }

        return $val ? sprintf('%s:%s', $key, $val) : null;
    }

    private function getAuthor()
    {
        /** @var ApiToken $apiToken */
        if ($apiToken = Auth::user()) {
            return $apiToken->api_token;
        }

        return nemid()->getRidNumber();
    }
}

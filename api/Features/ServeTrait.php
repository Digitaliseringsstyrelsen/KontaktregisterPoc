<?php

namespace Api\Features;

use Exception;
use Illuminate\Foundation\Bus\DispatchesJobs;

trait ServeTrait
{
    use DispatchesJobs, ValidationTrait, Permissions;

    public function serve($featureName, $attr)
    {
        $this->validatePermissions();
        /** @var Feature $instance */
        $instance = resolve($featureName);
        $mappers = $this->mapping();
        $map = collect($mappers->map)->filter(function($_, $val) use ($featureName) {
           return $featureName == $val;
        })->values();

        /**
         * Validate request base on resource.
         */
        $this->validator($map[0], $attr);

        /**
         * Here we set the values for our features properties.
         */
        foreach ($attr as $key => $value) {
            if (! property_exists($instance, $key)) {
                throw new Exception('Public variable not defined on'); // @codeCoverageIgnore
            }
            $instance->{$key} = $value;
        }

        return $instance->invoke();
    }
}

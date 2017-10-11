<?php

namespace Api\Features;

use Api\Exceptions\BadRequestException;
use Api\Resources\BaseResource;
use Api\Support\Enums;
use ReflectionClass;
use RuntimeException;
use Validator;

trait ValidationTrait
{
    public function validator($resource, $attr) : void
    {
        if (! $this->validateResource($resource)) {
            throw new BadRequestException(sprintf('Resource %s is not a valid resource', $resource)); // @codeCoverageIgnore
        }

        switch (request()->method()) {
            case 'POST':
            case 'PUT':
                $this->validateStore($resource, $attr);
                break;
            case 'GET':
                $this->validateGet($resource, $attr);
                break;
            default:
                break;
        }
    }

    private function validateResource($resource) : bool
    {
        return (new ReflectionClass($resource))->isSubclassOf(BaseResource::class);
    }

    /**
     * @todo this method is really overloaded, needs to clean up.
     * @param $resource
     * @param $attr
     */
    private function validateStore($resource, $attr) : void
    {
        $validationResource = new $resource;

        $required_fields = array_filter($validationResource->validation, function($val){
            return preg_match('/required/', $val) &&
                ! preg_match('/enum:/', $val) &&
                ! preg_match('/resource:/', $val);
        });

        $validation_rules = array_filter($validationResource->validation, function ($val) {
            return preg_match('/validation_rules:/', $val);
        });

        $required_enums = array_filter($validationResource->validation, function($val){
            return preg_match('/enum:/', $val);
        });

        $resources = array_filter($validationResource->validation, function ($val) {
            return preg_match('/resource:/', $val);
        });

        if (!empty($required_enums)) {
            $this->requiredEnumValidator($required_enums, $attr);
        }

        if (!empty($required_fields)) {
            $this->requiredFieldValidator($required_fields, $attr);
        }

        if (! empty($resources)) {
            $this->validateResources($resources, $attr);
        }

        if (! empty($validation_rules)) {
            $this->ruleValidator($validation_rules, $attr);
        }
    }

    public function requiredEnumValidator($enums, $attr) : void
    {
        foreach ($enums as $key => $enum) {
            if (! $enumVal = explode(':', $enum)[1]) {
                throw new RuntimeException('Enum is malformed.'); // @codeCoverageIgnore
            }

            if (! isset($attr[$key]) || ! $attr[$key]) {
                throw new BadRequestException(sprintf('Value: %s is empty or not set.', $key)); // @codeCoverageIgnore
            }

            /** @var Enums $enumInstance */
            $enumInstance = new $enumVal;

            if (! $enumInstance::isValidName($attr[$key])) {
                throw new BadRequestException(sprintf('%s is not a valid value for %s', $attr[$key], $enumVal));
            }
        }
    }

    public function requiredFieldValidator($required_fields, $attr)
    {
        $toValidate = array_filter($attr, function($key) use ($required_fields) {
            return array_key_exists($key, $required_fields);
        }, ARRAY_FILTER_USE_KEY);

        if (empty($toValidate)) {
            $toValidate = $attr;
        }

        $this->runValidation($toValidate, $required_fields);
    }

    private function runValidation(array $toValidate, array $required_fields)
    {
        $validator = Validator::make($toValidate, $required_fields);

        if ($validator->fails()) {
            throw new BadRequestException('Parameters fail to validate.');
        }
    }

    public function validateResources($resources, $attr) : void
    {
        foreach ($resources as $key => $resource) {
            if (! $resourceValue = explode(':', $resource)[1]) {
                throw new RuntimeException('Resource is malformed.'); // @codeCoverageIgnore
            }
            $this->validateStore($resourceValue, $attr[$key]);
        }
    }

    public function ruleValidator($rules, $attr) : void
    {
        $toValidate = array_filter($attr, function ($key) use ($rules) {
            return array_key_exists($key, $rules);
        }, ARRAY_FILTER_USE_KEY);

        if (! isset($toValidate['data'])) {
            throw new BadRequestException('Data is not defined.');
        }

        foreach ($rules as $rule_key => $rule_val) {
            if (! $rule = explode(':', $rule_val)[1]) {
                throw new RuntimeException('Rule is malformed.'); // @codeCoverageIgnore
            }

            $ruler = new $rule;

            $toValidateRules = array_filter($ruler->rules(), function ($key) use ($toValidate) {
                return array_key_exists($key, $toValidate['data']);
            }, ARRAY_FILTER_USE_KEY);

            /**
             * Validation of special cases when the rules are inside an array.
             * For example, address would contain an address array with the requirements to classify as a valid address.
             * This will should only be triggered when the validation rules contain a multidimensional array.
             * @todo after POC this needs to be extended so we do not need to terminate the method.
             */
            if (is_array($toValidateRules) && count($toValidateRules) !== count($toValidateRules, COUNT_RECURSIVE)) {
                $ruleKeys = array_keys($toValidateRules);
                foreach ($ruleKeys as $key) {
                    if (isset($toValidate['data'][$key]) && isset($toValidateRules[$key])) {
                        $this->runValidation($toValidate['data'][$key], $toValidateRules[$key]);
                    }
                }

                return;
            }

            $this->runValidation($toValidate['data'], $toValidateRules);
        }
    }

    private function validateGet($resource, $attr) : void
    {
        $validationResource = new $resource;

        $validatorRuler = [
            'filters',
        ];

        $required_fields = array_filter($validationResource->validation, function ($key) use ($validatorRuler) {
            list($val) = explode('.', $key);

            return in_array($val, $validatorRuler);
        }, ARRAY_FILTER_USE_KEY);

        if (! empty($required_fields)) {
            $this->requiredFieldValidator($required_fields, $attr);
        }
    }
}

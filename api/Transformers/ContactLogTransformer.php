<?php

namespace Api\Transformers;

use Api\Models\Log;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use RuntimeException;

class ContactLogTransformer extends Transformer
{
    private $modelsPath = 'Api\Models';

    public function transform(Log $entry)
    {
        return [
            'type'       => $entry->type,
            'old_value'  => $entry->old_value,
            'new_value'  => $entry->new_value,
            'occurrence' => $entry->created_at->toDateTimeString(),
        ];
    }

    public function handleValueTransformer($val)
    {
        if (! $val) {
            return $val;
        }

        $values = $this->calculateValues($val);

        if (substr($values->get('key'), -3) === '_id') {
            /**
             * If the log does have any relation to the any model, that means
             * that we can get the all relations in the log for a better display
             * of data.
             */
            $modelName = ucfirst(substr(camel_case($values->get('key')), 0, -2));
            $modelRef = sprintf('%s\%s', $this->modelsPath, $modelName);
            $model = new $modelRef();
            $result = $model->find($values->get('value'));

            if (! $result) {
                throw new RuntimeException('Model no longer exist.');
            }

            $transformer = sprintf('Api\Transformers\%sTransformer', $modelName);

            return $this->transformRelation($transformer, $result);
        }

        if (substr($values->get('key'), -3) === '_at') {
            return Carbon::parse($values->get('value'));
        }

        return $val;
    }

    private function calculateValues($val) : Collection
    {
        /**
         * For a fact we know if it made it this far down the system
         * it will have the convention for logs.
         */
        $result = preg_split('/(:)/', $val);

        /**
         * By default we assume that the logs first match it will describe
         * what kind of log it is.
         */
        $key = $result[0];

        /**
         * Therefor the reminder of the split will contain what ever
         * value the log does posses.
         */
        $reminder = substr($val, strlen($key) + 1);

        return collect([
            'key'   => $key,
            'value' => $reminder,
        ]);
    }
}

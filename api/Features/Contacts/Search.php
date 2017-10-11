<?php

namespace Api\Features\Contacts;

use Api\Features\Feature;
use Api\Models\Contact;
use Api\Transformers\ContactTransformer;

/**
 * @SWG\Definition(definition="search")
 */
class Search extends Feature
{
    /**
     * Query, this can be cpr/cvr numbers.
     * @var string
     * @SWG\Property()
     */
    public $query;
    /**
     * Filter to be applied in search.
     * @var array
     * @SWG\Property()
     */
    public $filters;
    protected $transformer = ContactTransformer::class;

    protected function handle()
    {
        $this->setCriteria($this->filters);
        $identifiers = explode(',', $this->query);
        /** @noinspection PhpDynamicAsStaticMethodCallInspection */
        $query = count($identifiers) > 1 ? 'whereIn' : 'where';
        $contacts = Contact::$query('identifier', $identifiers)
            ->when(array_has($this->filters, 'subscription_type'), function ($q) {
                return $q->whereHas('subscriptions', function ($q) {
                    return $q->whereHas('type', function ($q) {
                        return $q->where('name', array_get($this->filters, 'subscription_type'));
                    });
                });
            })
            ->get();

        $founds = $contacts->pluck('identifier')->toArray();

        foreach (array_filter($identifiers, function ($val) use ($founds) {
            return ! in_array($val, $founds);
        }) as $failed) {
            $this->setFailed(['type' => 'unknown', 'identifier' => $failed]);
        }

        return $contacts;
    }
}

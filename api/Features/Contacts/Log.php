<?php

namespace Api\Features\Contacts;

use Api\Features\Feature;
use Api\Transformers\ContactLogTransformer;
use Carbon\Carbon;

/**
 * @SWG\Definition(definition="contact-logs")
 */
class Log extends Feature
{
    protected $transformer = ContactLogTransformer::class;

    /**
     * @SWG\Property(ref="#definitions/Contact")
     */
    public $contact;

    /**
     * Filter search logs by a start date.
     * @var Carbon
     * @SWG\Property()
     */
    public $start;

    /**
     * Filter search logs by an end date.
     * @var Carbon
     * @SWG\Property()
     */
    public $end;

    /**
     * Filter search by type.
     * @var Carbon
     * @SWG\Property()
     */
    public $type;

    protected function handle()
    {
        return $this->contact->logs()
            ->when($this->start, function ($q) {
                $q->where('created_at', '>=', $this->start);
            })->when($this->end, function ($q) {
                $q->where('created_at', '<=', $this->end);
            })->when($this->type, function ($q) {
                $q->whereType($this->type);
            })->get();
    }
}

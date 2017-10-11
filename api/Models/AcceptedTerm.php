<?php

namespace Api\Models;

use Api\Contracts\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition()
 */
class AcceptedTerm extends Model implements Loggable
{
    use SoftDeletes;

    protected $fillable = [
        'accepted_at',
        'term_id',
    ];

    protected $dates = ['accepted_at'];

    public function terms()
    {
        return $this->belongsTo(Term::class, 'term_id');
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function loggableEntity(): Contact
    {
        return $this->subscription->owner;
    }
}

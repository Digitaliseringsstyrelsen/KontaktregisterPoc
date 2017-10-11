<?php

namespace Api\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition()
 */
class SubscriptionType extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

    public function terms()
    {
        return $this->hasMany(Term::class);
    }

    public function currentTerms()
    {
        return $this->terms()
            ->orderByVersion()
            ->get()
            ->last();
    }
}

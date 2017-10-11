<?php

namespace Api\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition()
 */
class Term extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'version',
        'text',
    ];

    public function subscriptionType()
    {
        return $this->belongsTo(SubscriptionType::class);
    }

    public function isLatestVersion()
    {
        return $this->subscriptionType->currentTerms()->id == $this->id;
    }

    public function scopeOrderByVersion($q)
    {
        return $q->orderByRaw('CAST(version as DECIMAL(10,5))');
    }
}

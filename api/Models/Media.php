<?php

namespace Api\Models;

use Api\Contracts\Loggable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition()
 */
class Media extends Model implements Loggable
{
    use SoftDeletes;

    protected $casts = [
        'data' => 'array',
    ];

    protected $fillable = [
        'type',
        'data',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function subscriptions()
    {
        return $this->belongsToMany(Subscription::class, 'media_subscriptions');
    }

    public function loggableEntity(): Contact
    {
        return $this->contact;
    }

    public function scopeCanBeForcefullyDeleted(Builder $query)
    {
        return $query->where('deleted_at', '<', Carbon::now()->subYears(5));
    }
}

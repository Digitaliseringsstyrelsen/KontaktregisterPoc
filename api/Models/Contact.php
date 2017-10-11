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
class Contact extends Model implements Loggable
{
    use SoftDeletes;

    protected $guarded = [
        'id',
    ];

    public function medias()
    {
        return $this->hasMany(Media::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'owner_contact_id', 'id');
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function loggableEntity(): Contact
    {
        return $this;
    }

    public function scopeCanBeForcefullyDeleted(Builder $query)
    {
        return $query->where('deleted_at', '<', Carbon::now()->subYears(5));
    }

    public function scopeRecentlyDeleted(Builder $query, $days = 14)
    {
        return $query->where('deleted_at', '>', Carbon::now()->subDays($days));
    }
}

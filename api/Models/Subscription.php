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
class Subscription extends Model implements Loggable
{
    use SoftDeletes;

    protected $fillable = [
        'subscription_type_id',
        'owner_contact_id',
        'source_contact_id',
        'started_at',
        'ended_at',
    ];

    protected $dates = [
        'started_at',
        'ended_at',
    ];

    public function type()
    {
        return $this->belongsTo(SubscriptionType::class, 'subscription_type_id');
    }

    public function medias()
    {
        return $this->belongsToMany(Media::class, 'media_subscriptions');
    }

    public function owner()
    {
        return $this->belongsTo(Contact::class, 'owner_contact_id');
    }

    public function source()
    {
        return $this->belongsTo(Contact::class, 'source_contact_id');
    }

    public function acceptedTerms()
    {
        return $this->hasMany(AcceptedTerm::class);
    }

    public function acceptTerms(Term $terms, Carbon $occurrence = null)
    {
        return $this->acceptedTerms()->create([
            'term_id' => $terms->id,
            'accepted_at' => $occurrence ?: Carbon::now(),
        ]);
    }

    public function loggableEntity(): Contact
    {
        return $this->owner;
    }

    public function scopeCanBeForcefullyDeleted(Builder $query)
    {
        return $query->where('deleted_at', '<', Carbon::now()->subYears(5));
    }

    public function scopeEndingWithin(Builder $query, $days = 30)
    {
        return $query->whereNotNull('ended_at')->whereBetween('ended_at', [Carbon::now(), Carbon::now()->addDays($days)]);
    }

    public function getLatestAcceptedTerm()
    {
        return $this->acceptedTerms()->latest('accepted_at')->first();
    }
}

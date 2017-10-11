<?php

namespace Api\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition()
 */
class MediaSubscription extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'subscription_id',
        'media_id',
        'subscribed_at',
    ];

    public $timestamps = false;

    protected $dates = [
        'subscribed_at',
    ];
}

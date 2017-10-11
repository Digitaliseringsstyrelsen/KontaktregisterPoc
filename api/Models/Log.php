<?php

namespace Api\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition()
 */
class Log extends Model
{
    public $timestamps = false;

    protected $dates = ['created_at'];

    protected $fillable = [
        'type',
        'user_token',
        'user_organization',
        'username',
        'old_value',
        'new_value',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Log $log) {
            $log->setCreatedAt($log->freshTimestamp());
        });
    }
}

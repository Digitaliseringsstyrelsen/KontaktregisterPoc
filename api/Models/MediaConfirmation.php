<?php

namespace Api\Models;

use Api\Contracts\Loggable;
use Illuminate\Database\Eloquent\Model;

class MediaConfirmation extends Model implements Loggable
{
    protected $observables = ['confirmed'];

    protected $casts = ['data' => 'array'];

    protected $fillable = [
        'type',
        'data',
        'code'
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function check($code)
    {
        return $this->code === (string) $code;
    }

    public function confirm()
    {
        if ($this->contact->medias()->create([
            'type' => $this->type,
            'data' => $this->data,
        ])) {
            $this->fireModelEvent('confirmed', false);
        }
    }

    public function loggableEntity(): Contact
    {
        return $this->contact;
    }
}

<?php

namespace Digist\Providers;

use Api\Models\MediaConfirmation;
use Digist\Observers\MediaConfirmationObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    public function boot()
    {
        MediaConfirmation::observe(MediaConfirmationObserver::class);
    }
}

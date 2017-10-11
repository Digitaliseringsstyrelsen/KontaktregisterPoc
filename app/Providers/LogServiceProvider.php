<?php

namespace Digist\Providers;

use Digist\Services\Log\Contracts\LoggerContract;
use Digist\Services\Log\Providers\QueueLogger;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class LogServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(LoggerContract::class, function (Application $_) {
            return resolve(QueueLogger::class);
        });
    }
}

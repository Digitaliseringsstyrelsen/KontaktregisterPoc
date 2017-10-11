<?php

namespace Digist\Console\Commands\Db;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Reset extends Command
{
    protected $signature = 'db:reset';

    protected $description = 'Drop all tables, run migrations and seed database';

    public function handle()
    {
        if (app()->environment('production')) {
            $this->warn('Resetting the database is not allowed in production');
            return;
        }

        $this->info('Nuking database');
        Artisan::call('db:nuke');
        $this->info('Running migrations');
        Artisan::call('migrate');
        $this->info('Seeding database');
        Artisan::call('db:seed');
    }
}

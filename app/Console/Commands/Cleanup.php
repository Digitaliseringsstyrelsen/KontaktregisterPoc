<?php

namespace Digist\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Cleanup extends Command
{
    protected $signature = 'cleanup
                            {--only= : Comma separated list of cleanup commands to run}';

    protected $description = 'Clean up data - Running this as a scheduled job is recommended';

    protected $availableCommands = [
        'force-delete-old-contacts'
    ];

    public function handle()
    {
        $commands = $this->option('only')
            ? array_intersect($this->availableCommands, explode(',', $this->option('only')))
            : $this->availableCommands;

        foreach ($commands as $command) {
            Artisan::call('cleanup:' . $command);
        }
    }
}

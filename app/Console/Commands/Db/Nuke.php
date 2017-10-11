<?php

namespace Digist\Console\Commands\Db;

use Illuminate\Console\Command;

class Nuke extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:nuke';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop all database tables';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (app()->environment('production')) {
            $this->warn('Nuking the database is not allowed in production');
            return;
        }

        $colname = 'Tables_in_' . env('DB_DATABASE');
        $tables = \DB::select('SHOW TABLES');
        \DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        foreach($tables as $table) {
            \DB::statement("DROP TABLE " . $table->$colname);
        }
        \DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}

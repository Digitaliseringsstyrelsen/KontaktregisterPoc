<?php

namespace Digist\Console\Commands\Cleanup;

use Api\Models\Contact;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Contracts\Logging\Log;

class ForceDeleteOldContacts extends Command
{
    protected $signature = 'cleanup:force-delete-old-contacts';

    protected $description = 'Forcefully delete 5 year old soft deleted contacts';

    protected $logger;

    public function __construct(Log $logger)
    {
        $this->logger = $logger;

        parent::__construct();
    }

    public function handle()
    {
        $affected = Contact::canBeForcefullyDeleted()->forceDelete();

        if ($affected) {
            $this->logger->info('Cleanup: Forcefully deleted ' . $affected . ' contacts that were deactivated over 5 years ago.');
        }
    }
}

<?php

namespace Digist\Jobs;

use Api\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddLogEntry implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $contact;
    protected $ridNumber;
    protected $type;
    protected $data;

    public function __construct(Contact $contact, string $ridNumber, string $type, array $data = [])
    {
        $this->contact = $contact;
        $this->ridNumber = $ridNumber;
        $this->type = $type;
        $this->data = $data;
    }

    public function handle()
    {
        $this->contact->logs()->create([
            'type' => $this->type,
            'user_token' => $this->ridNumber,
            'old_value' => array_get($this->data, 'old_value', null),
            'new_value' => array_get($this->data, 'new_value', null),
            'username' => '',
        ]);
    }
}

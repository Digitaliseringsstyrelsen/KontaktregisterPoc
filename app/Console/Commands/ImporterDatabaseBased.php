<?php

namespace Digist\Console\Commands;

use Api\Models\Contact;
use Api\Models\SubscriptionType;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;


class ImporterDatabaseBased extends Command
{
    use ImportHelper;

    protected $signature = 'ds:import 
                            {--addresses= : contact addresses file}';

    protected $description = 'Import contact database into Laravel models.';


    /**
     * Importing users
     * 2017-09-24 12:00:03
     * 100000/100000 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100%
     * Creating indexes
     * Contacts is done
     * Loading addresses
     * Query run for: 41 minutes
     * DONE
     */
    public function handle()
    {
        $this->info('Staring command');
        $start = Carbon::now();
        $count = 0;
        /** @noinspection PhpDynamicAsStaticMethodCallInspection */
        $digital_post = SubscriptionType::where('name', 'digital_post')->first();
        /** @noinspection PhpDynamicAsStaticMethodCallInspection */
        $reminder = SubscriptionType::where('name', 'reminder')->first();


        $this->warn('Importing users');
        $this->info($start->copy()->toDateTimeString());

        // We do not need this check for now..
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Lets create a temporal id to be able to reference back again.
        DB::unprepared(DB::raw('ALTER TABLE contacts ADD COLUMN contact_id INT DEFAULT NULL;'));

        $contacts = DB::connection('importer')->table('contacts');
        $bar = $this->output->createProgressBar($contacts->count());
        $bar->setRedrawFrequency(1);

        foreach ($contacts->cursor() as $contact) {
            /**
             * Users table.
             * - id [0]
             * - contact_id (cvr, cpr...) [1]
             * - logon_time [2]
             * - dkal_status integer 0: No 2:yes [3]
             * - DKAL_ACCEPT_TIME [4]
             * - SIDST_OPDATERET
             * - EBOKS_ROLLESTYRING
             * - NEMSMS_STATUS 0: no accepted 1: accepted [7]
             * - NEMSMS_ACCEPT_TIME
             * - NEMSMS_TILEMDT_ALT
             * - DKAL_TILMELDT_TID [10]
             * - NEMSMS_TILMELDT_TID
             * - SPAERRET
             * - SPAERRET_OPDATERET
             */
            DB::table('contacts')->insert([
                'contact_id' => $contact->contact_id,
                'type' => $this->getContactType($contact->foedtmmaa),
                'identifier' => $contact->foedtmmaa,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
            $contact_id = DB::getPdo()->lastInsertId();

            if (isset($contact->dk_status) && $contact->dk_status == 2) {
                DB::table('subscriptions')->insert([
                    'subscription_type_id' => $digital_post->id,
                    'owner_contact_id' => $contact_id,
                    'source_contact_id' => $contact_id,
                    'started_at' => $contact->nem_sms_timeld_tid != '0001-01-01-00.00.00.000000' ? Carbon::createFromFormat('Y-m-d-H.i.s.u', $contact->nem_sms_timeld_tid)->toDateTimeString() : null,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ]);
            }

            if (isset($contact->nem_sms_status) && $contact->nem_sms_status == 1) {
                DB::table('subscriptions')->insert([
                    'subscription_type_id' => $reminder->id,
                    'owner_contact_id' => $contact_id,
                    'source_contact_id' => $contact_id,
                    'started_at' => $contact->nem_sms_accepted_time != '0001-01-01-00.00.00.000000' ? Carbon::createFromFormat('Y-m-d-H.i.s.u', $contact->nem_sms_accepted_time) : null,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ]);
            }
            $bar->advance();
        }

        $bar->finish();

        $this->info('Creating indexes');
        DB::unprepared(DB::raw('CREATE INDEX contact_id ON contacts (contact_id);'));

        $this->info('Contacts is done');

        $this->warn('Loading addresses');
        // Lets do addresses.
        $addresses_file = $this->option('addresses');
        $addresses = fopen($addresses_file, 'r');

        do {
            $address = fgets($addresses);
            $address_options = array_map('trim', explode(';', $address));

            /**
             * string(1) "1" ID
             * string(1) "1" adress_type
             * string(1) "V" activ_code
             * string(26) "2015-12-27-14.17.45.592250" created_at
             * string(26) "0001-01-01-00.00.00.000000" deleted_at
             * string(100) "141591191A91451J544514L117H" CERTIFIKAT_ID
             * string(0) "" VERIFIKATIONSKODE
             * string(0) "" OPRINDELSE
             * string(1) "0" CERTIFIKAT_ID
             * string(1) "N" STATUS
             * string(26) "0001-01-01-00.00.00.000000" TIMESTAMP
             * string(1) "3" BRUGER_ID
             * string(1) "0" SYSTEM_ID
             * string(1) "0" ANTAL
             * string(26) "0001-01-01-00.00.00.000000" BEKRAEFT_UDSENDT
             * string(1) "1" adress_type
             * string(21) "tberntsen95@gmail.com" address
             */
            if (! isset($address_options[1])) {
                continue;
            }

            $count ++;

            if ($count % 10000 === 0) {
                $this->info(sprintf('Done %s in %s', $count,  $start->copy()->diffForHumans()));
            }

            $type = $this->getType($address_options[1]);
            /** @noinspection PhpDynamicAsStaticMethodCallInspection */
            if (! $contact = Contact::where('contact_id', $address_options[11])->first()) {
                $this->info(sprintf('Error: contact_id: %s; type: %s; address: %s', $address_options[11], $type, $address_options[16]));
                continue;
            }
            $contact->medias()->create([
                'type' => $type,
                'data' => $this->buildMediaData($type, $address_options[16]),
            ]);
        } while(! feof($addresses));
        fclose($addresses);


        DB::unprepared(DB::raw('ALTER TABLE contacts DROP COLUMN contact_id;'));
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $end = Carbon::now();

        $this->info('Query run for: ' . $start->copy()->diffForHumans($end));

        $this->info('DONE');
    }
}

<?php

namespace Digist\Console\Commands;

use Api\Models\Contact;
use Api\Models\Subscription;
use Api\Models\SubscriptionType;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImporterFileBased extends Command
{
    use ImportHelper;

    protected $signature = 'ds:import-file 
                            {--contacts= : contact files}
                            {--addresses= : contact addresses file}
                            {--fritagelse= : update fritagelse subscriptions}';

    protected $description = 'Import contact database into Laravel models.';


    /**
     * Staring command
     * Importing users
     * 2017-09-24 12:53:52
     * 100000/100000 [▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓] 100%
     * Creating indexes
     * Contacts is done
     * Loading addresses
     * Query run for: 27 minutes
     * DONE
     */
    public function handle()
    {
        $file = $this->option('contacts');
        $this->info('Staring command');
        $start = Carbon::now();

        /** @noinspection PhpDynamicAsStaticMethodCallInspection */
        $digital_post = SubscriptionType::where('name', 'digital_post')->first();
        /** @noinspection PhpDynamicAsStaticMethodCallInspection */
        $reminder = SubscriptionType::where('name', 'reminder')->first();

        $this->warn('Importing users');
        $this->info($start->copy()->toDateTimeString());

        // We do not need this check for now..
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::disableQueryLog();
        // Lets create a temporal id to be able to reference back again.
        DB::unprepared(DB::raw('ALTER TABLE contacts ADD COLUMN contact_id INT DEFAULT NULL;'));

        $users = fopen($file, 'r');
        $bar = $this->output->createProgressBar(100000);
        $bar->setRedrawFrequency(1);
        do {
            /**
             * Users table.
             * - id
             * - contact_id (cvr, cpr...)
             * - logon_time
             * - dkal_status integer 0: No 2:yes
             * - DKAL_ACCEPT_TIME
             * - SIDST_OPDATERET
             * - EBOKS_ROLLESTYRING
             * - NEMSMS_STATUS 0: no accepted 1: accepted
             * - NEMSMS_ACCEPT_TIME
             * - NEMSMS_TILEMDT_ALT
             * - DKAL_TILMELDT_TID
             * - NEMSMS_TILMELDT_TID
             * - SPAERRET
             * - SPAERRET_OPDATERET
             */

            $user = fgets($users);
            $user_option = array_map('trim', explode(';', $user));

            if (! isset($user_option[1])) {
                continue;
            }
            DB::transaction(function() use ($user_option, &$bar, $digital_post, $reminder) {
                DB::table('contacts')->insert([
                    'contact_id' => $user_option[0],
                    'type' => $this->getContactType($user_option[1]),
                    'identifier' => $user_option[1]
                ]);

                $contact_id = DB::getPdo()->lastInsertId();

                if (isset($user_option[3]) && $user_option[3] == 2) {
                    DB::table('subscriptions')->insert([
                        'subscription_type_id' => $digital_post->id,
                        'owner_contact_id' => $contact_id,
                        'source_contact_id' => $contact_id,
                        'started_at' => $user_option[11] != '0001-01-01-00.00.00.000000' ? Carbon::createFromFormat('Y-m-d-H.i.s.u',
                            $user_option[11]) : null,
                    ]);
                }

                if (isset($user_option[7]) && $user_option[7] == 1) {
                    DB::table('subscriptions')->insert([
                        'subscription_type_id' => $reminder->id,
                        'owner_contact_id' => $contact_id,
                        'source_contact_id' => $contact_id,
                        'started_at' => $user_option[8] != '0001-01-01-00.00.00.000000' ? Carbon::createFromFormat('Y-m-d-H.i.s.u', $user_option[8])->toDateTimeString() : null,
                    ]);
                }
                $bar->advance();
            });

        } while (! feof($users));

        $bar->finish();
        fclose($users);

        $this->info('Set time stamps');

        $now = Carbon::now()->toDateTimeString();
        DB::unprepared(DB::raw(sprintf('UPDATE subscriptions SET updated_at = "%s", created_at = "%s";', $now, $now)));
        DB::unprepared(DB::raw(sprintf('UPDATE contacts SET updated_at = "%s", created_at = "%s";', $now, $now)));

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

            if (! isset($type, $address_options[16]) || ! isset($type, $address_options[11]) || ! isset($type, $address_options[1])) {
                continue;
            }
            $type = $this->getType($address_options[1]);
            /** @noinspection PhpDynamicAsStaticMethodCallInspection */
            if (! $contact = Contact::where('contact_id', $address_options[11])->first()) {
                $this->info(sprintf('Error: bruger_id: %s; type: %s; address: %s', $address_options[11], $type,
                    $address_options[16]));
                continue;
            }
            $contact->medias()->create([
                'type' => $type,
                'data' => $this->buildMediaData($type, $address_options[16]),
            ]);
        } while (! feof($addresses));
        fclose($addresses);

        $fritagelse_file = $this->option('fritagelse');
        $this->info('Loading fritagelse');
        $fritagels = fopen($fritagelse_file, 'r');

        do {
            $fritagel = fgets($fritagels);
            $fritagel_options = explode(';', $fritagel);
            /**
             * string(1) "1" ID
             * string(1) "3" BRUGER_ID
             * string(26) "2015-12-27-14.17.45.592250" oprettet_tid
             * string(26) "2015-12-27-14.17.45.592250" ophaevet_tid
             * string(26) "2015-12-27-14.17.45.592250" UDLOEB_DATO
             * string(1) "J" UDLOEB_DATO J: Ja N: Nej
             * string(1) "P" FULDMAGT_IDENTTYPE P: privat V:Virksomhed
             * string(26) "J" FREMSEND_UAABNET  J: Ja N: Nej
             * string(26) "0" AARSAG 0: Ingen årsag 1: Udrejse 2: Anden årsag
             */
            if (! isset($fritagel_options[0])) {
                continue;
            }
            /** @noinspection PhpDynamicAsStaticMethodCallInspection */
            $contact = Contact::where('contact_id', $fritagel_options[0])->first();
            if (! $contact || ! $contact->subscriptions) {
                continue;
            }

            $contact->subscriptions()->each(function (Subscription $subscription) use ($fritagel_options) {
                if (! $subscription) {
                    return;
                }

                try {
                    DB::transaction(function () use ($subscription, $fritagel_options) {
                        $new_subscription = $subscription->replicate(['start_date']);
                        $subscription->ended_at = $fritagel_options[3] != '0001-01-01-00.00.00.000000' ?
                            Carbon::createFromFormat('Y-m-d-H.i.s.u', $fritagel_options[3])->toDateTimeString() :
                            null;
                        $subscription->save();
                        $new_subscription->started_at = $fritagel_options[4] != '9999-12-31' ? Carbon::createFromFormat($fritagel_options[4],
                            'Y-m-d') : Carbon::now()->addYear(2);
                        $new_subscription->save();
                    });
                }catch (\InvalidArgumentException $exception) {
                    return;
                }
            });
        } while (! feof($fritagels));
        fclose($fritagels);

        $this->info('Done fritagelse');

        $this->info('Dropping temporal fields');
        DB::unprepared(DB::raw('ALTER TABLE contacts DROP COLUMN contact_id;'));

        $this->info('Enable locks again.');
        DB::enableQueryLog();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('DONE: ' . Carbon::now()->toDateTimeString());
    }
}

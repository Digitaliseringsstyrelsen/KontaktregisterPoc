<?php

use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Api\Models\Contact::all()->each(function (Api\Models\Contact $contact) {
            Api\Models\SubscriptionType::all()->each(function (Api\Models\SubscriptionType $type) use ($contact) {
                $subscription = $contact->subscriptions()->create([
                    'subscription_type_id' => $type->id,
                    'source_contact_id' => $contact->id,
                    'started_at' => Carbon\Carbon::today(),
                ]);
                $subscription->medias()->attach($contact->medias[array_rand($contact->medias->toArray())]->id, ['subscribed_at' => Carbon\Carbon::now()]);
                $subscription->acceptTerms($type->currentTerms(), $subscription->started_at);
            });
        });
    }
}

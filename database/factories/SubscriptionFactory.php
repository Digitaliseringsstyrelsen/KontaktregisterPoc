<?php

$factory->define(Api\Models\Subscription::class, function (Faker\Generator $faker) {
    return [
        'subscription_type_id' => function () {
            return factory(Api\Models\SubscriptionType::class)->create()->id;
        },
        'owner_contact_id' => function () {
            return factory(Api\Models\Contact::class)->create();
        },
        'source_contact_id' => function () {
            return factory(Api\Models\Contact::class)->create();
        },
        'started_at' => Carbon\Carbon::today(),
        'ended_at' => null,
    ];
});

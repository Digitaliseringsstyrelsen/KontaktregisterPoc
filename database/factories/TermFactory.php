<?php

$factory->define(Api\Models\Term::class, function (Faker\Generator $faker) {
    return [
        'version' => $faker->randomNumber(1) . '.' . $faker->randomNumber(2),
        'text' => $faker->paragraphs(3, true),
        'subscription_type_id' => function () {
            return factory(\Api\Models\SubscriptionType::class)->create()->id;
        },
    ];
});

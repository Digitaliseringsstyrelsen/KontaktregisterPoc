<?php

$factory->define(Api\Models\SubscriptionType::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
    ];
});

<?php

$factory->define(Api\Models\Contact::class, function (Faker\Generator $faker) {
    return [
        'type' => 'person',
        'identifier' => $faker->date('dmy') . $faker->randomNumber(4, true),
    ];
});

$factory->state(Api\Models\Contact::class, 'company', function (Faker\Generator $faker) {
    return [
        'type' => 'company',
        'identifier' => $faker->randomNumber(8, true),
    ];
});

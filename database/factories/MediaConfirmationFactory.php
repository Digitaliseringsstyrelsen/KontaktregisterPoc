<?php

$factory->define(Api\Models\MediaConfirmation::class, function (Faker\Generator $faker) {
    return [
        'contact_id' => function () {
            return factory(Api\Models\Contact::class)->create()->id;
        },
        'type' => 'dummy',
        'data' => ['value' => $faker->randomNumber(1)],
        'code' => $faker->randomNumber(6, true),
    ];
});

$factory->state(Api\Models\MediaConfirmation::class, 'phone', function (Faker\Generator $faker) {
    return [
        'type' => 'phone',
        'data' => ['number' => $faker->randomNumber(8, true)],
    ];
});

$factory->state(Api\Models\MediaConfirmation::class, 'mobile_device', function (Faker\Generator $faker) {
    return [
        'type' => 'mobile_device',
        'data' => ['arn' => $faker->randomNumber(8, true)],
    ];
});

$factory->state(Api\Models\MediaConfirmation::class, 'email', function (Faker\Generator $faker) {
    return [
        'type' => 'email',
        'data' => ['email' => $faker->email],
    ];
});

$factory->state(Api\Models\MediaConfirmation::class, 'physical_address', function (Faker\Generator $faker) {
    return [
        'type' => 'physical_address',
        'data' => [
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'address' => $faker->streetAddress,
            'zipcode' => $faker->randomNumber(4, true),
            'city' => $faker->city,
            'country' => $faker->countryCode,
        ],
    ];
});

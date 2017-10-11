<?php

$factory->define(Api\Models\Media::class, function (Faker\Generator $faker) {
    return [
        'type' => 'dummy',
        'data' => ['value' => $faker->randomNumber(1)],
        'contact_id' => function () {
            return factory(Api\Models\Contact::class)->create()->id;
        },
    ];
});

$factory->state(Api\Models\Media::class, 'sms', function (Faker\Generator $faker) {
    return [
        'type' => 'sms',
        'data' => ['number' => $faker->randomNumber(8, true)],
    ];
});

$factory->state(Api\Models\Media::class, 'email', function (Faker\Generator $faker) {
    return [
        'type' => 'email',
        'data' => ['email' => $faker->email],
    ];
});

$factory->state(Api\Models\Media::class, 'physical_address', function (Faker\Generator $faker) {
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

<?php

$factory->define(\Digist\Models\ApiToken::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'api_token' => $faker->randomAscii,
        'description' => $faker->paragraph,
    ];
});

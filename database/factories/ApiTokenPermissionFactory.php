<?php

$factory->define(\Digist\Models\ApiTokenPermission::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->name,
        'granted_at' => \Carbon\Carbon::today(),
        'revoked_at'  => null,
        'expired_at'  => \Carbon\Carbon::today()->addWeek(),
        'permission_key' => \Digist\Models\ApiTokenPermission::READ,
        'api_token_id' => factory(\Digist\Models\ApiToken::class)->create()->id,
    ];
});

<?php

use Illuminate\Database\Seeder;

class TermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Api\Models\SubscriptionType::all()->each(function (Api\Models\SubscriptionType $type) {
            factory(Api\Models\Term::class, 5)->create(['subscription_type_id' => $type->id]);
        });
    }
}

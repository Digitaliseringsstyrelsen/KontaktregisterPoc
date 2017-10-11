<?php

use Illuminate\Database\Seeder;

class SubscriptionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\Api\Models\SubscriptionType::class)->create(['name' => 'notification']);
        factory(\Api\Models\SubscriptionType::class)->create(['name' => 'reminder']);
        factory(\Api\Models\SubscriptionType::class)->create(['name' => 'digital_post']);
    }
}

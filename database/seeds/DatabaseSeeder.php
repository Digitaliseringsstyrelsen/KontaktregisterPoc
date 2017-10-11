<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SubscriptionTypeSeeder::class);

        //if (! app()->environment('production')) {
        //    $this->call(TermSeeder::class);
        //    $this->call(ContactSeeder::class);
        //}
    }
}

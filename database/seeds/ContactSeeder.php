<?php

use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Api\Models\Contact::class)->create(['identifier' => '1111111111']);
        factory(Api\Models\Contact::class)->states(['company'])->create(['identifier' => '11111111']);
        factory(Api\Models\Contact::class, 2000)->create();
        factory(Api\Models\Contact::class, 2000)->states(['company'])->create();

        $this->call(MediaSeeder::class);
        $this->call(SubscriptionSeeder::class);
    }
}

<?php

use Illuminate\Database\Seeder;

class MediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Api\Models\Contact::all()->each(function ($contact) {
            factory(Api\Models\Media::class)->states(['sms'])->create(['contact_id' => $contact->id]);
            factory(Api\Models\Media::class)->states(['email'])->create(['contact_id' => $contact->id]);
        });
    }
}

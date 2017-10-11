<?php

namespace Tests\Feature;

use Api\Models\Contact;
use Api\Models\Media;
use Api\Models\Subscription;
use Tests\TestCase;

class RouteBinderTest extends TestCase
{
    /**
     * @test
     */
    public function accessContactsWrongSubscription()
    {
        $contact = factory(Contact::class)->create();
        $anotherSubscription = factory(Subscription::class)->create();
        $this->get('api/contacts/' . $contact->identifier . '/subscriptions/' . $anotherSubscription->id)
            ->assertStatus(400)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'code',
                    'message',
                ],
            ]);
    }

    /**
     * @test
     */
    public function deleteOtherContactMedia()
    {
        $contact = factory(Contact::class)->create();
        $media = factory(Media::class)->states(['email'])->create(['contact_id' => $contact->id]);
        $wrongContact = factory(Contact::class)->create();
        $this->delete('api/contacts/' . $wrongContact->identifier . '/medias/' . $media->id)
            ->assertStatus(400)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'code',
                    'message',
                ],
            ]);;
    }
}

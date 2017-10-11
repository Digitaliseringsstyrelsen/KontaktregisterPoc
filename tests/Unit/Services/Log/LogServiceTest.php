<?php

namespace Tests\Unit\Services\Log;

use Api\Models\Contact;
use Api\Models\Media;
use Api\Models\Subscription;
use Api\Models\SubscriptionType;
use Carbon\Carbon;
use Tests\TestCase;

class LogServiceTest extends TestCase
{
    /**
     * @test
     */
    public function logPostRequest()
    {
        $this->post('/api/contacts', [
            'type' => 'person',
            'identifier' => '0000000000',
        ])->assertStatus(201);
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function logCreatedSubscription()
    {
        $contact = factory(Contact::class)->create();
        $subscription = factory(Subscription::class)->create([
            'subscription_type_id' => factory(SubscriptionType::class)->create()->id,
            'owner_contact_id' => $contact->id,
            'source_contact_id' => $contact->id,
        ]);

        $this->put('/api/contacts/' . $contact->identifier . '/subscriptions/' . $subscription->id,  [
            'end_at' => Carbon::tomorrow(),
        ])->assertStatus(200);

        $getContact = Contact::find($contact->id);
        $this->assertEquals(1, $getContact->logs->count());
        $log = $getContact->logs->first();
        $this->assertNull($log->old_value);
        $this->assertEquals($log->new_value, sprintf('ended_at:%s', Carbon::tomorrow()));
    }

    /**
     * @test
     */
    public function createLogWithMedia()
    {
        $subscription = factory(Subscription::class)->create();
        $contact = $subscription->owner;
        $media = factory(Media::class)->states(['email'])->create(['contact_id' => $contact->id]);

        $this->post(route('contacts.media-subscriptions.store', [$contact->identifier]), [
            'subscription_id' => $subscription->id,
            'media_id' => $media->id,
        ])->assertStatus(201);

        $this->get(route('contacts.log', [$contact->identifier]))
            ->assertStatus(200);
    }
}

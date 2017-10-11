<?php

namespace Tests\Feature;

use Api\Models\Media;
use Api\Models\Subscription;
use Carbon\Carbon;
use Tests\TestCase;

class SubscriptionMediaTest extends TestCase
{
    /**
     * @test
     */
    public function create()
    {
        $subscription = factory(Subscription::class)->create();
        $contact = $subscription->owner;
        $media = factory(Media::class)->states(['email'])->create(['contact_id' => $contact->id]);

        $this->post(route('contacts.media-subscriptions.store', [$contact->identifier]), [
            'subscription_id' => $subscription->id,
            'media_id' => $media->id,
        ])->assertStatus(201)->assertJsonStructure([
            'id',
            'type',
            'medias' => [
                [
                    'id',
                    'type',
                    'data' => [
                        'email',
                    ],
                ],
            ],
        ]);
    }

    /**
     * @test
     */
    public function createWithUnassociatedMediaFails()
    {
        $subscription = factory(Subscription::class)->create();
        $contact = $subscription->owner;
        $media = factory(Media::class)->states(['email'])->create();

        $this->post(route('contacts.media-subscriptions.store', [$contact->identifier]), [
            'subscription_id' => $subscription->id,
            'media_id' => $media->id,
        ])->assertStatus(404);
    }

    /**
     * @test
     */
    public function createWithNonExistingMediaFails()
    {
        $subscription = factory(Subscription::class)->create();
        $contact = $subscription->owner;

        $this->post(route('contacts.media-subscriptions.store', [$contact->identifier]), [
            'subscription_id' => $subscription->id,
            'media_id' => 0,
        ])->assertStatus(400);
    }

    /**
     * @test
     */
    public function deleteMediaFromSubscription()
    {
        $subscription = factory(Subscription::class)->create();
        $contact = $subscription->owner;
        $media = factory(Media::class)->states(['email'])->create(['contact_id' => $contact->id]);

        $subscription->medias()->attach($media->id, ['subscribed_at' => Carbon::now()]);

        $this->delete(route('contacts.media-subscriptions.destroy', [$contact->identifier]), [
            'subscription_id' => $subscription->id,
            'media_id' => $media->id,
        ])->assertStatus(204);
    }

    /**
     * @test
     */
    public function deleteUnattachedMediaFromSubscription()
    {
        $subscription = factory(Subscription::class)->create();
        $contact = $subscription->owner;
        $media = factory(Media::class)->states(['email'])->create();

        $this->delete(route('contacts.media-subscriptions.destroy', [$contact->identifier]), [
            'subscription_id' => $subscription->id,
            'media_id' => $media->id,
        ])->assertStatus(404)->assertSee(sprintf('Media %s is not associated with subscription %s', $media->id, $subscription->id));
    }
}

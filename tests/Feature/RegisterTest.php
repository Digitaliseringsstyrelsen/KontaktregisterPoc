<?php

namespace Tests\Feature;

use Api\Models\Contact;
use Api\Models\Media;
use Api\Models\Subscription;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function registerFail()
    {
        $contact = factory(Contact::class)->make();
        $media = factory(Media::class)->make();
        $subscription = factory(Subscription::class)->make();
        $this->post('/api/register', [
            'contact' => [
                'type'       => $contact->type,
                'identifier' => $contact->identifier,
            ],

            'media'        => [
                'type' => $media->type,
                'data' => $media->data,
            ],
            'subscription' => [
                'subscription_type_id' => $subscription->subscription_type_id,
            ],
        ])
            ->assertStatus(400);
    }

    public function mediaStateDataProvider()
    {
        return [
            ['email'],
            ['sms']
        ];
    }

    /**
     * @test
     * @dataProvider mediaStateDataProvider
     * @param string $state
     */
    public function register($state)
    {
        $contact = factory(Contact::class)->make();
        $media = factory(Media::class)->states([$state])->make();
        $subscription = factory(Subscription::class)->make();
        $this->post('/api/register', [
            'contact' => [
                'type'       => $contact->type,
                'identifier' => $contact->identifier,
            ],

            'media'        => [
                'type' => $media->type,
                'data' => $media->data,
            ],
            'subscription' => [
                'subscription_type_id' => $subscription->subscription_type_id,
            ],
        ])
            ->assertStatus(201)
            ->assertJsonStructure([
                'type',
                'identifier',
                'medias'        => [],
                'subscriptions' => [],
            ]);
    }
}

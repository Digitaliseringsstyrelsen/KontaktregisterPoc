<?php

namespace Tests\Feature;

use Api\Models\Contact;
use Api\Models\Media;
use Api\Models\Subscription;
use Api\Models\SubscriptionType;
use Api\Models\Term;
use Carbon\Carbon;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    /**
     * @test
     */
    public function createSubscription()
    {
        $contact = factory(Contact::class)->create()->identifier;
        $subscription_type = factory(SubscriptionType::class)->create()->id;
        $source_contact = factory(Contact::class)->create();
        $response = $this->post('/api/contacts/' . $contact . '/subscriptions', [
            'subscription_type_id' => $subscription_type,
            'source_contact_id' => $source_contact->identifier,
        ])->assertStatus(201)->assertJsonStructure([
            'id',
            'type',
            'medias' => [],
            'accepted_terms' => [],
            'source_contact' => [
                'type',
                'identifier',
            ],
        ])->json();

        $this->assertEquals($source_contact->identifier, $response['source_contact']['identifier']);
    }

    /**
     * @test
     */
    public function updateSourceContact()
    {
        $contact = factory(Contact::class)->create();
        $subscription_type = factory(SubscriptionType::class)->create()->id;
        $source_contact = factory(Contact::class)->create();
        $this->post('/api/contacts/' . $contact->identifier . '/subscriptions', [
            'subscription_type_id' => $subscription_type,
        ])->assertStatus(201);
        $createdSubscription = Subscription::where('subscription_type_id', $subscription_type)->where('owner_contact_id', $contact->id)->first();
        $this->assertNotNull($createdSubscription);
        $response = $this->put('/api/contacts/' . $contact->identifier . '/subscriptions/' . $createdSubscription->id, [
            'source_contact_id' => $source_contact->id,
        ])->assertStatus(200)->json();

        $this->assertEquals($source_contact->identifier, $response['source_contact']['identifier']);
    }

    /**
     * @test
     */
    public function createSubscriptionWithOutSource()
    {
        $contact = factory(Contact::class)->create()->identifier;
        $subscription_type = factory(SubscriptionType::class)->create()->id;
        $this->post('/api/contacts/' . $contact . '/subscriptions', [
            'subscription_type_id' => $subscription_type,
        ])->assertStatus(201);
    }

    /**
     * @test
     */
    public function createOverlappingSubscription()
    {
        $contact = factory(Contact::class)->create();
        $subscription = factory(Subscription::class)->create([
            'owner_contact_id' => $contact->id,
            'source_contact_id' => $contact->id,
            'started_at' => Carbon::yesterday(),
            'ended_at' => Carbon::now()->addMonth(),
        ]);
        $this->post('/api/contacts/' . $contact->identifier . '/subscriptions', [
            'subscription_type_id' => $subscription->subscription_type_id,
            'start_at' => Carbon::now(),
        ])->assertStatus(400)->assertSee('Start date overlaps with an existing subscription');
    }

    /**
     * @test
     */
    public function getContactSubscriptions()
    {
        $subscription = factory(Subscription::class)->create();
        factory(Term::class)->create([
            'subscription_type_id' => $subscription->type->id
        ]);
        $contact = $subscription->owner;
        $subscription->medias()->attach(factory(Media::class)->states(['sms'])->create([
            'contact_id' => $contact->id
        ]), ['subscribed_at' => Carbon::now()]);
        $subscription->acceptTerms($subscription->type->currentTerms());

        $this->get('api/contacts/' . $contact->identifier . '/subscriptions')
            ->assertJsonStructure([
                'total',
                'per_page',
                'current_page',
                'last_page',
                'next_page_url',
                'prev_page_url',
                'from',
                'to',
                'data' => [
                    [
                        'id',
                        'type',
                        'medias' => [
                            [
                                'id',
                                'type',
                                'data',
                            ],
                        ],
                        'accepted_terms' => [
                            'id',
                            'version',
                            'latest',
                            'accepted_at',
                        ],
                    ],
                ],
            ])->assertStatus(200);
    }

    /**
     * @test
     */
    public function getSingleContactSubscription()
    {
        $subscription = factory(Subscription::class)->create();
        factory(Term::class)->create([
            'subscription_type_id' => $subscription->type->id
        ]);
        $contact = $subscription->owner;
        $subscription->medias()->attach(factory(Media::class)->states(['sms'])->create([
            'contact_id' => $contact->id
        ]), ['subscribed_at' => Carbon::now()]);
        $subscription->acceptTerms($subscription->type->currentTerms());

        $this->get('api/contacts/' . $contact->identifier . '/subscriptions/' . $subscription->id)
            ->assertJsonStructure([
                'id',
                'type',
                'medias' => [
                    [
                        'id',
                        'type',
                        'data',
                    ],
                ],
                'accepted_terms' => [
                    'id',
                    'version',
                    'latest',
                    'accepted_at',
                ],
            ])->assertStatus(200);
    }

    /**
     * @test
     */
    public function getSubscriptionsFromNonExistingContact()
    {
        $this->get('api/contacts/0/subscriptions')->assertStatus(404);
    }

    /**
     * @test
     */
    public function getNonOwnedSubscription()
    {
        $subscription = factory(Subscription::class)->create();
        $contact2 = factory(Contact::class)->create();
        $this->get('api/contacts/' . $contact2->id . '/subscriptions/' . $subscription->id)->assertStatus(404);
    }

    /**
     * @test
     */
    public function destroyUserSubscription()
    {
        $contact = factory(Contact::class)->create();
        $subscription = factory(Subscription::class)->create([
            'owner_contact_id' => $contact->id,
            'source_contact_id' => $contact->id,
        ]);
        $this->delete('api/contacts/' . $contact->identifier . '/subscriptions/' . $subscription->id)
            ->assertStatus(204);
    }
}

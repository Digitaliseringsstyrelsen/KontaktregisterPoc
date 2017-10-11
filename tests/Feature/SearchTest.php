<?php

namespace Tests\Feature;

use Api\Models\Contact;
use Api\Models\Subscription;
use Api\Models\SubscriptionType;
use Tests\TestCase;

class SearchTest extends TestCase
{
    /**
     * @test
     */
    public function batchSearch()
    {
        $subscription_type = factory(SubscriptionType::class)->create();
        $contacts = [];

        foreach (factory(Contact::class, 6)->create() as $contact) {
            $contacts[] = $contact->identifier;
            factory(Subscription::class)->create([
                'subscription_type_id' => $subscription_type->id,
                'owner_contact_id' => $contact->id,
                'source_contact_id' => $contact->id,
            ]);
        }

        $response = json_decode(
            $this->search(implode(',', $contacts), ['subscription_type' => $subscription_type->name])
                ->assertStatus(200)
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
                            'type',
                            'identifier',
                            'medias' => [],
                            'subscriptions' => [
                                [
                                    'id',
                                    'type',
                                    'medias' => [],
                                ],
                            ],
                        ],
                    ],
                ])->baseResponse->content(),
            true
        );

        $this->assertEquals(6, $response['total']);

        foreach ($response['data'] as $contact) {
            $this->assertEquals($subscription_type->name, $contact['subscriptions'][0]['type']);
        }
    }

    public function search($query, array $filters = [])
    {
        return $this->get('api/search?query=' . $query . '&' . http_build_query(['filters' => $filters]));
    }

    /**
     * @test
     */
    public function searchWithFailedResult()
    {
        $nonExisting = [
            '332211334431112333',
            '001122334455',
        ];
        $response = $this->search(implode(',', array_merge(factory(Contact::class, 2)
            ->create()
            ->pluck('identifier')
            ->toArray(),
            $nonExisting
        )))
            ->assertStatus(200);
        $response = json_decode($response->baseResponse->content());
        $this->assertEquals(2, $response->total);
        $this->assertEquals(2, count($response->failed));

        foreach ($response->failed as $key => $value) {
            $this->assertTrue(in_array($value->identifier, $nonExisting));
        }
    }
}

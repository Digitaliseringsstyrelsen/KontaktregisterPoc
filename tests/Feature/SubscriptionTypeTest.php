<?php

namespace Tests\Feature;

use Api\Models\SubscriptionType;
use Api\Models\Term;
use Tests\TestCase;

class SubscriptionTypeTest extends TestCase
{
    /**
     * @test
     */
    public function listSubscriptionTypes()
    {
        $type = factory(SubscriptionType::class)->create();
        factory(Term::class, 2)->create([
            'subscription_type_id' => $type->id
        ]);

        $this->get('api/subscription-types')
            ->assertStatus(200)
            ->assertJsonStructure([
                'total',
                'per_page',
                'last_page',
                'next_page_url',
                'prev_page_url',
                'from',
                'to',
                'data' => [
                    [
                        'id',
                        'name',
                        'terms' => [
                            [
                                'id',
                                'version',
                                'text',
                                'subscription_type' => [
                                    'id',
                                    'name',
                                ],
                            ]
                        ],
                    ],
                ],
            ]);
    }
}

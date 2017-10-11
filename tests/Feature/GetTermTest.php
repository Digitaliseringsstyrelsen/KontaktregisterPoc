<?php

namespace Tests\Feature;

use Api\Models\SubscriptionType;
use Api\Models\Term;
use Tests\TestCase;

class GetTermTest extends TestCase
{
    /**
     * @test
     */
    public function indexTerms()
    {
        factory(Term::class, 5)->create();
        $this->get('api/terms')
            ->assertJsonStructure([
                'total',
                'per_page',
                'last_page',
                'next_page_url',
                'prev_page_url',
                'from',
                'to',
                'data' => [],
            ])->assertStatus(200);
    }

    /**
     * @test
     */
    public function getSingleTerm()
    {
        $term = factory(Term::class)->create([
            'subscription_type_id' => factory(SubscriptionType::class)->create()->id,
        ]);

        $this->get(sprintf('api/terms/%s', $term->id))
            ->assertJsonStructure([
                'id',
                'version',
                'text',
                'subscription_type' => [],
            ])->assertStatus(200);
    }
}

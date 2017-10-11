<?php

namespace Tests\Feature;

use Api\Models\Contact;
use Api\Models\Media;
use Tests\TestCase;

class MediaTest extends TestCase
{
    /**
     * @test
     */
    public function createMedia()
    {
        $contact = factory(Contact::class)->create();
        $this->post('/api/contacts/' . $contact->identifier . '/medias', [
            'type' => 'email',
            'data' => [
                'email' => resolve(\Faker\Generator::class)->email
            ],
        ])->assertJsonStructure([
            'id',
            'type',
            'data' => [
                'email'
            ],
        ])->assertStatus(201);

        $this->assertEquals(1, $contact->medias()->count());
    }

    /**
     * @test
     */
    public function createMoreMediaThanPermitted()
    {
        $contact = factory(Contact::class)->create();
        factory(Media::class, 2)->states(['email'])->create([
            'contact_id' => $contact->id,
        ]);
        $this->post('/api/contacts/' . $contact->identifier . '/medias', [
            'type' => 'email',
            'data' => [
                'email' => resolve(\Faker\Generator::class)->email
            ],
        ])->assertStatus(400);
    }

    /**
     * @test
     */
    public function listContactMedias()
    {
        $contact = factory(Contact::class)->create();
        factory(Media::class, 3)->states(['email'])->create(['contact_id' => $contact->id]);

        $response = $this->get(route('contacts.medias.index', [$contact->identifier]))
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
                        'id',
                        'type',
                        'data' => [
                            'email',
                        ],
                    ],
                ],
            ])->baseResponse->content();

        $this->assertCount(3, json_decode($response, true)['data']);
    }

    /**
     * @test
     */
    public function deleteMedia()
    {
        $contact = factory(Contact::class)->create();
        $media = factory(Media::class)->states(['email'])->create(['contact_id' => $contact->id]);

        $this->delete('api/contacts/' . $contact->identifier . '/medias/' . $media->id)->assertStatus(204);
    }

    /**
     * @test
     */
    public function createMediaFail()
    {
        $contact = factory(Contact::class)->create();
        $this->post('/api/contacts/' . $contact->identifier . '/medias', [
            'type' => 'email',
            'data' => [
                'email' => 'no-real_email',
            ],
        ])->assertStatus(400);
    }

    /**
     * @test
     */
    public function createAddressMediaFail()
    {
        $contact = factory(Contact::class)->create();
        $this->post('/api/contacts/' . $contact->identifier . '/medias', [
            'type' => 'email',
            'data' => [
                'address' => [
                    'city' => 'Copenhagen',
                ],
            ],
        ])->assertStatus(400);
    }

    /**
     * @test
     */
    public function createAddressSuccess()
    {
        $contact = factory(Contact::class)->create();
        $this->post('/api/contacts/' . $contact->identifier . '/medias', [
            'type' => 'address',
            'data' => [
                'address' => [
                    'address' => 'Vej 112',
                    'city' => 'Copenhagen',
                    'post_code' => '2100',
                ],
            ],
        ])->assertStatus(201);
    }

    /**
     * @test
     */
    public function updateMedia()
    {
        $contact = factory(Contact::class)->create();
        $media = factory(Media::class)->states(['email'])->create([
            'contact_id' => $contact->id,
        ]);

        $response = $this->put('/api/contacts/' . $contact->identifier . '/medias/' . $media->id, [
            'type' => 'email',
            'data' => [
                'email' => resolve(\Faker\Generator::class)->email,
            ],
        ])
            ->assertStatus(200)
            ->json();

        $key = array_keys($media->data)[0];
        $value = array_values($media->data)[0];
        $this->assertEquals($response['type'], array_keys($media->data)[0]);
        $this->assertNotEquals($response['data'][$key], $value);
    }

    /**
     * @test
     * @dataProvider wrongTypeDataProvider
     * @param $type
     * @param $key
     */
    public function updateContactMediaWithWrongType($type, $key)
    {
        $contact = factory(Contact::class)->create();
        $media = factory(Media::class)->states(['email'])->create([
            'contact_id' => $contact->id,
        ]);

        $this->put('/api/contacts/' . $contact->identifier . '/medias/' . $media->id, [
            'type' => $type,
            'data' => [
                $key => resolve(\Faker\Generator::class)->email,
            ],
        ])->assertStatus(400);
    }

    public function wrongTypeDataProvider()
    {
        return [
            ['sms', 'email'],
            ['physical_address', 'number'],
        ];
    }
}

<?php

namespace Tests\Feature;

use Api\Models\Contact;
use Api\Models\Media;
use Digist\Models\ApiToken;
use Digist\Models\ApiTokenPermission;
use Tests\TestCase;

class BasicAuthTest extends TestCase
{
    /**
     * @test
     */
    public function testBasicAuth()
    {
        $apiToken = factory(ApiToken::class)->create();
        factory(ApiTokenPermission::class)->create([
            'api_token_id' => $apiToken->id,
        ]);

        $contact = factory(Contact::class)->create();

        factory(Media::class)->states(['sms'])->create([
            'contact_id' => $contact->id,
        ]);

        $this->get(route('contacts.show', [$contact->identifier]), [
            'Authorization' => 'Basic ' . base64_encode(sprintf('%s:%s', $apiToken->name, $apiToken->api_token)),
        ], false)
            ->assertStatus(200)
            ->assertJsonStructure([
                'identifier',
                'type',
                'medias' => [
                    [
                        'id',
                        'type',
                    ]
                ],
                'subscriptions' => [],
            ]);
    }

    /**
     * @test
     */
    public function getUnWashMediaForContactWithApiToken()
    {
        $apiToken = factory(ApiToken::class)->create();

        factory(ApiTokenPermission::class)->create([
            'api_token_id' => $apiToken->id,
        ]);

        factory(ApiTokenPermission::class)->create([
            'api_token_id' => $apiToken->id,
            'permission_key' => 'un_washed',
        ]);

        $contact = factory(Contact::class)->create();

        factory(Media::class)->states(['sms'])->create([
            'contact_id' => $contact->id,
        ]);

        $this->get(route('contacts.show', [$contact->identifier]), [
            'Authorization' => 'Basic ' . base64_encode(sprintf('%s:%s', $apiToken->name, $apiToken->api_token)),
        ], false)
            ->assertStatus(200)
            ->assertJsonStructure([
                'identifier',
                'type',
                'medias' => [
                    [
                        'id',
                        'type',
                        'data',
                    ]
                ],
                'subscriptions' => [],
            ]);
    }

    /**
     * @test
     */
    public function getWashedDataByPermissions()
    {
        $apiToken = factory(ApiToken::class)->create();

        factory(ApiTokenPermission::class)->create([
            'api_token_id' => $apiToken->id,
        ]);

        $this->get(route('contacts.show', [factory(Contact::class)->create()->identifier]), [
            'Authorization' => 'Basic ' . base64_encode(sprintf('%s:%s', $apiToken->name, $apiToken->api_token)),
        ], false)
            ->assertStatus(200)
            ->assertJsonStructure([
                'identifier',
                'type',
                'medias' => [],
                'subscriptions' => [],
            ]);
    }

    /**
     * @test
     */
    public function failedGetContact()
    {
        $apiToken = factory(ApiToken::class)->create();
        $this->get(route('contacts.show', [factory(Contact::class)->create()->identifier]), [
            'Authorization' => 'Basic ' . base64_encode(sprintf('%s:%s', $apiToken->name, $apiToken->api_token)),
        ], false)->assertStatus(401);
    }

    /**
     * @test
     */
    public function createContactWithToken()
    {
        $apiToken = factory(ApiToken::class)->create();

        factory(ApiTokenPermission::class)->create([
            'api_token_id' => $apiToken->id,
            'permission_key' => 'write',
        ]);

        $this->post('/api/contacts', [
            'type' => 'person',
            'identifier' => '0202021199',
        ], [
            'Authorization' => 'Basic ' . base64_encode(sprintf('%s:%s', $apiToken->name, $apiToken->api_token)),
        ], false)->assertStatus(201)->assertJsonStructure([
            'type',
            'identifier',
            'medias' => [],
            'subscriptions' => [],
        ]);
    }

    /**
     * @test
     */
    public function failedToCreateContactWithToken()
    {
        $apiToken = factory(ApiToken::class)->create();

        factory(ApiTokenPermission::class)->create([
            'api_token_id' => $apiToken->id,
        ]);

        $this->post('/api/contacts', [
            'type' => 'person',
            'identifier' => '0202021199',
        ], [
            'Authorization' => 'Basic ' . base64_encode(sprintf('%s:%s', $apiToken->name, $apiToken->api_token)),
        ], false)->assertStatus(401);
    }
}

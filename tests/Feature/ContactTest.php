<?php

namespace Tests\Feature;

use Api\Models\Contact;
use Digist\Services\Log\Service as Logger;
use Tests\TestCase;

class ContactTest extends TestCase
{
    /**
     * @dataProvider successCreateContactDataProvider
     * @test
     * @param $type
     * @param $identifier
     */
    public function createContact($type, $identifier)
    {
        $this->post('/api/contacts', [
            'type' => $type,
            'identifier' => $identifier,
        ])->assertStatus(201)->assertJsonStructure([
            'type',
            'identifier',
            'medias' => [],
            'subscriptions' => [],
        ]);
    }

    /**
     * @dataProvider failCreateContactDataProvider
     * @test
     * @param $type
     * @param $identifier
     */
    public function createContactFail($type, $identifier)
    {
        $this->post('/api/contacts', [
            'type' => $type,
            'identifier' => $identifier,
        ])->assertStatus(400);
    }

    /**
     * @test
     */
    public function showContact()
    {
        $this->get(route('contacts.show', [factory(Contact::class)->create()->identifier]))
            ->assertStatus(200)
            ->assertJsonStructure([
                'identifier',
                'type',
                'medias' => [],
                'subscriptions' => [],
            ]);
    }

    public function successCreateContactDataProvider()
    {
        return [
            ['person', '989898989'],
            ['company', '334422442']
        ];
    }

    public function failCreateContactDataProvider()
    {
        return [
            ['failing', '989898989'],
            ['company', 33]
        ];
    }

    /** @test */
    public function getContactLog()
    {
        $contact = factory(Contact::class)->create();

        /** @var Logger $logger */
        $logger = resolve(Logger::class);

        $logger->log($contact, '', 'POST', [
            'old_value' => 'foo',
            'new_value' => 'bar',
        ]);

        $response = $this->get(route('contacts.log', [$contact->identifier]))
            ->assertStatus(200)->assertJsonStructure([
                'total',
                'per_page',
                'current_page',
                'data' => [
                    [
                        'type',
                        'old_value',
                        'new_value',
                        'occurrence',
                    ],
                ],
            ])->json();

        $this->assertEquals(1, $response['total']);
        $this->assertEquals('foo', $response['data'][0]['old_value']);
        $this->assertEquals('bar', $response['data'][0]['new_value']);
    }
}

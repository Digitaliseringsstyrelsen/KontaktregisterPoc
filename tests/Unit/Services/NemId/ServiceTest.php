<?php

namespace Tests\Unit\Models;

use Api\Models\Contact;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    /**
     * @test
     * @dataProvider payloadProvider
     */
    public function check($path, $identifier, $privileges)
    {
        nemid()->login(file_get_contents($path));

        $this->assertTrue(nemid()->check());
        $this->assertEquals($identifier, nemid()->id());

        foreach ($privileges as $scope => $scope_privileges) {
            foreach ($scope_privileges as $privilege) {
                $this->assertTrue(nemid()->privilege($scope, $privilege));
            }
        }
    }

    public function payloadProvider()
    {
        return [
            [
                'tests/nemid_payloads/Borger_uden rettigheder.xml',
                '2202653459',
                [],
            ],
            [
                'tests/nemid_payloads/Myndighedsbruger_med admin rettighed.xml',
                '34051178',
                [
                    34051178 => ['privilegie_admin']
                ],
            ],
            [
                'tests/nemid_payloads/Myndighedsbruger_uden admin rettighed.xml',
                '34051178',
                [],
            ],
            [
                'tests/nemid_payloads/Virksomhedsbruger_med ekstern kontaktregister rettighed.xml',
                '29634750',
                [
                    34388156 => ['privilegie_kontaktregister']
                ],
            ],
            [
                'tests/nemid_payloads/Virksomhedsbruger_med kontaktregister rettighed.xml',
                '29634750',
                [
                    29634750 => ['privilegie_kontaktregister'],
                ],
            ],
            [
                'tests/nemid_payloads/Virksomhedsbruger_med tegningsret og kontaktregister rettighed.xml',
                '3012501111',
                [
                    29634750 => ['privilegie_kontaktregister'],
                ],
            ],
            [
                'tests/nemid_payloads/Virksomhedsbruger_uden kontaktregister rettighed.xml',
                '29634750',
                [],
            ],
        ];
    }

    /**
     * @test
     */
    public function canApplicationCreateContactWithAuth()
    {
        $contact = factory(Contact::class)->create();
        $this->post('/api/contacts/' . $contact->identifier . '/medias', [
            'type' => 'email',
            'data' => [
                'email' => resolve(\Faker\Generator::class)->email
            ],
        ], [
            'X-NemId-Auth' => file_get_contents('tests/nemid_payloads/Borger_uden rettigheder.xml'),
        ])->assertStatus(201);
    }
}

<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions;

    protected $headers = [];

    protected $nem_id_admin_payload_file = 'tests/nemid_payloads/Myndighedsbruger_med admin rettighed.xml';

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
    }

    public function signIn()
    {
        return $this->signInAs($this->nem_id_admin_payload_file);
    }

    public function signInAs($payloadFile)
    {
        return $this->loadNemIdPayload($payloadFile);
    }

    public function get($uri, array $headers = [],  $internal_headers = true)
    {
        return parent::get($uri, $internal_headers ? $this->headers : $headers);
    }

    public function post($uri, array $data = [], array $headers = [],  $internal_headers = true)
    {
        return parent::post($uri, $data, $internal_headers ? $this->headers : $headers);
    }

    public function put($uri, array $data = [], array $headers = [])
    {
        return parent::put($uri, $data, $this->headers);
    }

    public function delete($uri, array $data = [], array $headers = [])
    {
        return parent::delete($uri, $data, $this->headers);
    }

    public function loadNemIdPayload($path)
    {
        $this->headers['Authorization'] = 'NemID ' . file_get_contents($path);

        return $this;
    }
}

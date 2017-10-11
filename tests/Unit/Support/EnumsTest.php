<?php

namespace Tests\Unit\Enums;

use Api\Support\ContactTypes;
use Tests\TestCase;

class EnumsTest extends TestCase
{
    /**
     * @test
     */
    public function isValidName()
    {
        $this->assertTrue(ContactTypes::isValidName('PERSON'));
        $this->assertTrue(ContactTypes::isValidName('Person'));
        $this->assertTrue(ContactTypes::isValidName('person'));
    }

    /**
     * @test
     */
    public function isValidNameStrict()
    {
       $this->assertTrue(ContactTypes::isValidName('PERSON', true));
       $this->assertFalse(ContactTypes::isValidName('Person', true));
       $this->assertFalse(ContactTypes::isValidName('person', true));
    }

    /**
     * @test
     */
    public function isValidValue()
    {
        $this->assertTrue(ContactTypes::isValidValue('person'));
        $this->assertFalse(ContactTypes::isValidValue('Person'));
    }

    /**
     * @test
     */
    public function getValue()
    {
        // Using constants
        $this->assertEquals('person', ContactTypes::PERSON);
        $this->assertEquals('company', ContactTypes::COMPANY);

        // Using getValue()
        $this->assertEquals('person', ContactTypes::getValue('PERSON'));
        $this->assertEquals('company', ContactTypes::getValue('COMPANY'));

        // Using getValue() - falling back to default value
        $this->assertEquals('person', ContactTypes::getValue('foo'));
    }
}

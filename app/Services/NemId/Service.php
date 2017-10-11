<?php

namespace Digist\Services\NemId;

use Api\Exceptions\BadRequestException;
use Api\Models\Contact;

class Service
{
    public $identifier;

    protected $assurance_level;

    protected $contact;

    protected $privileges = [];

    protected $ridNumber;

    protected function load($xml)
    {
        if (! is_xml($xml)) {
            throw new \InvalidArgumentException('NemID payload must be in XML format');
        }

        $document = simplexml_load_string($xml);

        foreach ($document->AttributeStatement->Attribute as $attribute) {
            if ($attribute['Name'] == 'dk:gov:saml:attribute:AssuranceLevel') {
                $this->assurance_level = (string)$attribute->AttributeValue;
            }

            if (in_array($attribute['Name'], [
                'dk:gov:saml:attribute:CprNumberIdentifier',
                'dk:gov:saml:attribute:CvrNumberIdentifier',
            ])) {
                $this->identifier = (string)$attribute->AttributeValue;
            }

            if (in_array($attribute['Name'], [
                'dk:gov:saml:attribute:RidNumberIdentifier',
            ])) {
                $this->ridNumber = (string)$attribute->AttributeValue;
            }

            if ($attribute['Name'] == 'dk:gov:saml:attribute:Privileges_intermediate') {
                $this->privileges = $this->xmlToPrivileges($this->decode($attribute->AttributeValue));
            }
        }
    }

    protected function decode($encoded_payload)
    {
        return base64_decode((string)$encoded_payload);
    }

    protected function xmlToPrivileges($xml)
    {
        $document = simplexml_load_string($xml);

        $privileges = [];

        foreach ($document->PrivilegeGroup as $group) {
            $privilege_group = [];

            foreach ($group->Privilege as $privilege) {
                $privilege_group[] = preg_replace('/(urn:dk:(\w+))/s', '$2', (string) $privilege);
            }

            $privileges[(string) preg_replace('/(urn:dk:gov:saml:(Cvr|Cpr)NumberIdentifier:(\d+))/s', '$3', $group['Scope'])] = $privilege_group;
        }

        return $privileges;
    }

    public function login($payload)
    {
        $this->load($payload);

        return $this;
    }

    public function check()
    {
        return ! is_null($this->id());
    }

    public function user()
    {
        return $this->contact ?: $this->contact = Contact::whereIdentifier($this->id())->first();
    }

    public function id()
    {
        return $this->identifier;
    }

    public function admin()
    {
        return $this->privilege($this->id(), 'privilegie_admin');
    }

    public function assuranceLevel()
    {
        return $this->assurance_level;
    }

    public function privileges()
    {
        return $this->privileges;
    }

    public function canAccess($scope)
    {
        return $this->admin() || ! empty($this->privilegesOn($scope));
    }

    public function privilegesOn($scope)
    {
        return array_get($this->privileges(), $scope, []);
    }

    public function privilege($scope, $privilege)
    {
        return in_array($privilege, $this->privilegesOn($scope));
    }

    public function getRidNumber()
    {
        if (! $this->ridNumber) {
            throw new BadRequestException('Auth number is missing');
        }

        return $this->ridNumber;
    }
}

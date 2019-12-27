<?php

namespace Uloc\ApiBundle\Tests;

use Uloc\ApiBundle\Entity\Person\Person;
use Uloc\ApiBundle\Entity\Person\TypeAddressPurpose;
use Uloc\ApiBundle\Entity\Person\TypeEmailPurpose;

class PersonManagerTest extends AbstractFuncionalTest
{
    public function testNewPerson()
    {
        $personManager = $this->container->get('uloc_api.manager.person_manager');

        $person = $personManager
            ->disablePersist()
            ->create('Tiago', 1, true, [
                'address' => [
                    [
                        'address' => 'Rua Dr Antonio Augusto Veloso',
                        'complement' => 'AP 304',
                        'number' => '112',
                        'district' => 'Morada do Sol',
                        'districtId' => '283736',
                        'zip' => '39401804',
                        'city' => 'Montes Claros',
                        'cityId' => '0001b',
                        'state' => 'Minas Gerais',
                        'stateId' => '0001',
                        'otherPurpose' => 'Outros',
                        'default' => true,
                        'latitude' => '123',
                        'longitude' => '321',
                        'type' => (new TypeAddressPurpose())->setName('Type Address 1')
                    ]
                ],
                'emails' => [
                    [
                        'email' => 'tiago@tiagofelipe.com',
                        'valid' => true,
                        'default' => true,
                        'otherPurpose' => 'Other',
                        'type' => (new TypeEmailPurpose())->setName('Type Email 1')
                    ]
                ]
            ]);

        $this->assertInstanceOf(Person::class, $person);
        $this->assertEquals('Tiago', $person->getName());

        /* @var \Uloc\ApiBundle\Entity\Person\Address $address */
        $address = $person->getAddresses()[0];
        $this->assertEquals('Rua Dr Antonio Augusto Veloso', $address->getAddress());
        $this->assertEquals('AP 304', $address->getComplement());
        $this->assertEquals('112', $address->getNumber());
        $this->assertEquals('Morada do Sol', $address->getDistrict());
        $this->assertEquals('283736', $address->getDistrictId());
        $this->assertEquals('39401804', $address->getZip());
        $this->assertEquals('Montes Claros', $address->getCity());
        $this->assertEquals('0001b', $address->getCityId());
        $this->assertEquals('Minas Gerais', $address->getState());
        $this->assertEquals('0001', $address->getStateId());
        $this->assertEquals('Outros', $address->getOtherPurpose());
        $this->assertEquals(true, $address->getDefault());
        $this->assertEquals('123', $address->getLatitude());
        $this->assertEquals('321', $address->getLongitude());
        $this->assertEquals('Type Address 1', $address->getPurpose()->getName());

        /* @var \Uloc\ApiBundle\Entity\Person\ContactEmail $email */
        $email = $person->getEmails()[0];
        $this->assertEquals('tiago@tiagofelipe.com', $email->getEmail());
        $this->assertEquals(true, $email->getValid());
        $this->assertEquals(true, $email->getDefault());
        $this->assertEquals('Other', $email->getOtherPurpose());
        $this->assertEquals('Type Email 1', $email->getPurpose()->getName());
    }
}
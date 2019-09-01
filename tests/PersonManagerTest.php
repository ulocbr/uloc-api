<?php

namespace Uloc\ApiBundle\Tests;

use Uloc\ApiBundle\Entity\Person\Person;

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
                        // 'type' => 1 #todo: active test
                    ]
                ]
            ]);
        $this->assertInstanceOf(Person::class, $person);
        $this->assertEquals('Tiago', $person->getName());
        $this->assertEquals('Rua Dr Antonio Augusto Veloso', $person->getAddresses()[0]->getAddress());
    }
}
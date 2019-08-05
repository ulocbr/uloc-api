<?php

namespace Uloc\ApiBundle\Tests;

use Uloc\ApiBundle\Entity\Person\Person;

class PersonManagerTest extends AbstractFuncionalTest
{
    public function testNewPerson()
    {
        $personManager = $this->container->get('uloc_api.manager.person_manager');

        $person = $personManager->createPerson('Tiago');
        $this->assertInstanceOf(Person::class, $person);
    }
}
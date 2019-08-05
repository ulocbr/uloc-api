<?php

namespace Uloc\ApiBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Uloc\ApiBundle\Entity\Person\Person;
use Uloc\ApiBundle\Manager\Model\CustomManager;

class PersonManager extends CustomManager implements PersonManagerInterface
{
    /**
     * Create an new Person
     * @param string $name
     * @param int $type
     * @param bool $active
     * @return Person
     */
    public function createPerson(string $name, int $type = 1, bool $active = true)
    {
        $person = new Person();
        return $person;
    }

}
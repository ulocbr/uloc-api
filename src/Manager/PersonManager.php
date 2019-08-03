<?php

namespace Uloc\ApiBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;

class PersonManager implements PersonManagerInterface
{
    private $om;

    /**
     * PersonManager constructor.
     * @param object $om    ObjectManager para persistência
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }


    /**
     * Create an new Person
     * @param string $name
     * @param int $type
     * @param bool $active
     */
    public function createPerson(string $name, int $type = 1, bool $active = true)
    {

    }

}
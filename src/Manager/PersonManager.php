<?php

namespace Uloc\ApiBundle\Manager;

use Uloc\ApiBundle\Entity\Person\Person;
use Uloc\ApiBundle\Entity\Person\PersonDocument;
use Uloc\ApiBundle\Entity\Person\RegistrationOrigin;
use Uloc\ApiBundle\Entity\Person\TypePersonDocument;
use Uloc\ApiBundle\Manager\Model\CustomManager;

class PersonManager extends CustomManager implements PersonManagerInterface
{
    public function create(string $name, int $type = 1, bool $active = true, array $extras = null, array $options = null)
    {
        $person = new Person();
        $person->setName($name);
        $person->setType($type);
        $person->setActive($active);

        /**
         * Set Extra Data
         */

        /**
         * Options
         */

        $this->persist($person);
        $this->flush();

        return $person;
    }

    public function find(int $id)
    {
        // TODO: Implement find() method.
    }
}
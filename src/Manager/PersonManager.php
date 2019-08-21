<?php

namespace Uloc\ApiBundle\Manager;

use Uloc\ApiBundle\Entity\Person\Person;
use Uloc\ApiBundle\Manager\Model\CustomManager;

class PersonManager extends CustomManager implements PersonManagerInterface
{
    public function create(string $name, int $type = 1, bool $active = true, array $extras = null, array $options = null)
    {
        return new Person();
    }

    public function find(int $id)
    {
        // TODO: Implement find() method.
    }

    public function manager(Person $person)
    {
        // TODO: Implement manager() method.
    }

    public function isManaging()
    {
        // TODO: Implement isManaging() method.
    }

    public function update()
    {
        // TODO: Implement update() method.
    }

    public function remove()
    {
        // TODO: Implement remove() method.
    }

    public function list(int $limit, int $offset = 0, $filter = null)
    {
        // TODO: Implement list() method.
    }

    public function search(string $search)
    {
        // TODO: Implement search() method.
    }


}
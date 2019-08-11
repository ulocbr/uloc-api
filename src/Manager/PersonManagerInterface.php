<?php
/**
 * Created by PhpStorm.
 * User: Tiago
 * Date: 25/10/2018
 * Time: 16:41
 */

namespace Uloc\ApiBundle\Manager;

use Uloc\ApiBundle\Entity\Person\Person;

interface PersonManagerInterface
{

    /**
     * Create an new Person
     *
     * @param string $name
     * @param int $type
     * @param bool $active
     * @param array $extras
     * @param array $options
     * @return mixed
     */
    public function create(string $name, int $type = 1, bool $active = true, array $extras, array $options);

    /**
     * Find and return an Person entity
     *
     * @param int $id
     * @return Person|null
     */
    public function find(int $id);

    /**
     * Manage a Person
     *
     * @param Person $person
     * @return self
     */
    public function manager(Person $person);

    /**
     * Check if is managing a Person
     *
     * @return boolean
     */
    public function isManaging();

    /**
     * Update a Person managed
     * This method is best of ->persist and ->flush of ObjectManager becaus here call same events and features when
     * update an person
     * @return Person
     */
    public function update();

    /**
     * Remove a Person entity
     *
     * @return boolean
     */
    public function remove();

    /**
     * List Persons
     *
     * @param int $limit
     * @param int $offset
     * @param null $filter
     * @return mixed
     */
    public function list(int $limit, int $offset = 0, $filter = null);

    /**
     * Smart Person search
     *
     * @param string $search
     * @return array|Person
     */
    public function search(string $search);


}
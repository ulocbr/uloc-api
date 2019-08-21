<?php
/**
 * Created by PhpStorm.
 * User: Tiago
 * Date: 25/10/2018
 * Time: 16:41
 */

namespace Uloc\ApiBundle\Manager;

use Uloc\ApiBundle\Entity\Person\Address;
use Uloc\ApiBundle\Entity\Person\Person;
use Uloc\ApiBundle\Entity\Person\PersonDocument;
use Uloc\ApiBundle\Entity\Person\RegistrationOrigin;
use Uloc\ApiBundle\Entity\Person\TypeAddressPurpose;
use Uloc\ApiBundle\Entity\Person\TypePersonDocument;

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
     * @return Person
     *
     *
     */
    public function create(string $name, int $type = 1, bool $active = true, array $extras = null, array $options = null);

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
     * Update a managed Person managed
     * This method is best of ->persist and ->flush of ObjectManager because here call same events and features when
     * update an person
     * @return Person
     */
    public function update();

    /**
     * Remove a managed Person entity
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

    /**
     * Update person classification number
     *
     * @param int $classification
     * @return mixed
     */
    public function updateClassification(int $classification);

    public function createRegistrationOrigin($name, $description, $extra = null);
    public function findRegistrationOrigin(int $id);
    public function updateRegistrationOrigin(RegistrationOrigin $registrationOrigin);
    public function removeRegistrationOrigin(RegistrationOrigin $registrationOrigin);
    public function listRegistrationOrigins(int $limit = null, int $offset = 0, $filter = null);

    public function addDocument($identifier, $agentDispatcher, $type = null);
    public function findDocument(int $id);
    public function updateDocument(PersonDocument $document);
    public function removeDocument(PersonDocument $document);
    public function listDocuments(int $limit = null, int $offset = 0, $filter = null);

    public function createTypePersonDocument($name);
    public function findTypePersonDocument(int $id);
    public function updateTypePersonDocument(TypePersonDocument $type);
    public function removeTypePersonDocument(TypePersonDocument $type);
    public function listTypePersonDocuments(int $limit = null, int $offset = 0, $filter = null);

    public function addAddress($address, $complement = null, $number = null, $district = null, $districtId = null, $zip = null, $city = null, $cityId = null, $state = null, $stateId = null, $otherPurpose = null, $default = false, $latitude = null, $longitude = null, $type = null);
    public function findAddress(int $id);
    public function updateAddress(Address $address);
    public function removeAddress(Address $address);
    public function listAddresses(int $limit = null, int $offset = 0, $filter = null);

    public function createTypeAddressPurpose($name);
    public function findTypeAddressPurpose(int $id);
    public function updateTypeAddressPurpose(TypeAddressPurpose $type);
    public function removeTypeAddressPurpose(TypeAddressPurpose $type);
    public function listTypeAddressPurposes(int $limit = null, int $offset = 0, $filter = null);

}
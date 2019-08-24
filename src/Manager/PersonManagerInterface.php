<?php
/**
 * Created by PhpStorm.
 * User: Tiago
 * Date: 25/10/2018
 * Time: 16:41
 */

namespace Uloc\ApiBundle\Manager;

use Uloc\ApiBundle\Entity\Person\Address;
use Uloc\ApiBundle\Entity\Person\ContactEmail;
use Uloc\ApiBundle\Entity\Person\ContactExtra;
use Uloc\ApiBundle\Entity\Person\ContactPhone;
use Uloc\ApiBundle\Entity\Person\ExtraField;
use Uloc\ApiBundle\Entity\Person\Note;
use Uloc\ApiBundle\Entity\Person\Paper;
use Uloc\ApiBundle\Entity\Person\Person;
use Uloc\ApiBundle\Entity\Person\PersonDocument;
use Uloc\ApiBundle\Entity\Person\PersonExtraField;
use Uloc\ApiBundle\Entity\Person\RegistrationOrigin;
use Uloc\ApiBundle\Entity\Person\Tag;
use Uloc\ApiBundle\Entity\Person\TypeAddressPurpose;
use Uloc\ApiBundle\Entity\Person\TypeContactExtraPurpose;
use Uloc\ApiBundle\Entity\Person\TypeEmailPurpose;
use Uloc\ApiBundle\Entity\Person\TypePaper;
use Uloc\ApiBundle\Entity\Person\TypePersonDocument;
use Uloc\ApiBundle\Entity\Person\TypePhonePurpose;

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

    public function addPaper(Person $person, $type, \DateTime $start = null, \DateTime $end = null);
    public function findPaper(int $id);
    public function endPaper(Paper $paper);
    public function updatePaper(Paper $paper);
    public function removePaper(Paper $paper);
    public function listPapers(int $limit = null, int $offset = 0, $filter = null);

    public function createTypePaper($name, $code = null);
    public function findTypePaper(int $id);
    public function updateTypePaper(TypePaper $type);
    public function removeTypePaper(TypePaper $type);
    public function listTypePapers(int $limit = null, int $offset = 0, $filter = null);

    public function addNote($annotation, $label = null);
    public function findNote(int $id);
    public function updateNote(Note $type);
    public function removeNote(Note $type);
    public function listNotes(int $limit = null, int $offset = 0, $filter = null);

    public function addPhone($areaCode, $number, $cellphone = false, $default = false, $im = null, $otherPurpose = null, $type = null);
    public function findPhone(int $id);
    public function updatePhone(ContactPhone $phone);
    public function removePhone(ContactPhone $phone);
    public function listPhones(int $limit = null, int $offset = 0, $filter = null);

    public function createTypePhonePurpose($name);
    public function findTypePhonePurpose(int $id);
    public function updateTypePhonePurpose(TypePhonePurpose $type);
    public function removeTypePhonePurpose(TypePhonePurpose $type);
    public function listTypePhonePurposes(int $limit = null, int $offset = 0, $filter = null);

    public function addEmail($email, $valid = true, $default = false, $otherPurpose = null, $type = null);
    public function findEmail(int $id);
    public function updateEmail(ContactEmail $email);
    public function removeEmail(ContactEmail $email);
    public function listEmails(int $limit = null, int $offset = 0, $filter = null);

    public function createTypeEmailPurpose($name);
    public function findTypeEmailPurpose(int $id);
    public function updateTypeEmailPurpose(TypeEmailPurpose $type);
    public function removeTypeEmailPurpose(TypeEmailPurpose $type);
    public function listTypeEmailPurposes(int $limit = null, int $offset = 0, $filter = null);

    public function addContactExtra($name, $tag, $value, $label = null, $type = null);
    public function findContactExtra(int $id);
    public function updateContactExtra(ContactExtra $contact);
    public function removeContactExtra(ContactExtra $contact);
    public function listContactExtras(int $limit = null, int $offset = 0, $filter = null);

    public function createTypeContactExtraPurpose($name);
    public function findTypeContactExtraPurpose(int $id);
    public function updateTypeContactExtraPurpose(TypeContactExtraPurpose $type);
    public function removeTypeContactExtraPurpose(TypeContactExtraPurpose $type);
    public function listTypeContactExtraPurposes(int $limit = null, int $offset = 0, $filter = null);

    public function addExtraField($name, $code = null, $description = null, $required = true);
    public function findExtraField(int $id);
    public function updateExtraField(ExtraField $extraField);
    public function removeExtraField(ExtraField $extraField);
    public function listExtraFields(int $limit = null, int $offset = 0, $filter = null);

    public function addPersonExtraField(ExtraField $field, $value);
    public function findPersonExtraField(int $id);
    public function updatePersonExtraField(PersonExtraField $contact);
    public function removePersonExtraField(PersonExtraField $contact);
    public function listPersonExtraFields(int $limit = null, int $offset = 0, $filter = null);

    public function addTag($name, $description = null);
    public function findTag(int $id);
    public function updateTag(Tag $tag);
    public function removeTag(Tag $tag);
    public function listTags(int $limit = null, int $offset = 0, $filter = null);

    public function communicate(); // TODO
    public function registerHistoryCommunicate(); // TODO
    public function findCommunicate(int $id);
    public function listCommunicates(int $limit = null, int $offset = 0, $filter = null);

}
<?php

namespace Uloc\ApiBundle\Manager;

use Uloc\ApiBundle\Entity\Person\Address;
use Uloc\ApiBundle\Entity\Person\ContactEmail;
use Uloc\ApiBundle\Entity\Person\Contact;
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
use Uloc\ApiBundle\Entity\Person\TypeContactPurpose;
use Uloc\ApiBundle\Entity\Person\TypeEmailPurpose;
use Uloc\ApiBundle\Entity\Person\TypePaper;
use Uloc\ApiBundle\Entity\Person\TypePersonDocument;
use Uloc\ApiBundle\Entity\Person\TypePhonePurpose;
use Uloc\ApiBundle\Manager\Model\CustomManager;

class PersonManager extends CustomManager implements PersonManagerInterface
{
    /* @var Person */
    private $person;

    public function create(string $name, int $type = 1, bool $active = true, array $extras = null, array $options = null)
    {
        $person = is_array($extras) && $extras['entity'] ?  $extras['entity'] : new Person();
        $person->setName($name);
        $person->setType($type);
        $person->setActive($active);

        // self-manager
        $this->manager($person);
        $enablePersist = false;
        if ($this->persistEnabled) {
            $this->disablePersist();
            $enablePersist = true;
        }

        /**
         * Set Extra Data
         * Possibilites: Address, Emails, Phones, Contacts
         */
        if (is_array($extras)) {

            /**
             * Primary Document
             */
            if (isset($extras['document'])) {
                $person->setDocument($extras['document']);
            }

            /**
             * Addresses
             * Need parse 'address' key to $extras with array contains one or more address
             */
            if (isset($extras['address'])) {
                foreach ($extras['address'] as $address) {
                    $this->addAddress(
                        $address['address'],
                        @$address['complement'],
                        @$address['number'],
                        @$address['district'],
                        @$address['districtId'],
                        @$address['zip'],
                        @$address['city'],
                        @$address['cityId'],
                        @$address['state'],
                        @$address['stateId'],
                        @$address['otherPurpose'],
                        @$address['default'],
                        @$address['latitude'],
                        @$address['longitude'],
                        @$address['type']
                    );
                }
            }

            /**
             * Emails
             * Need parse 'email' key to $extras with array contains one or more emails
             */
            if (isset($extras['emails'])) {
                foreach ($extras['emails'] as $email) {
                    $this->addEmail(
                        $email['email'],
                        @$email['valid'],
                        @$email['default'],
                        @$email['otherPurpose'],
                        @$email['type']
                    );
                }
            }

            /**
             * Phones
             * Need parse 'phone' key to $extras with array contains one or more phones
             */
            if (isset($extras['phones'])) {
                foreach ($extras['phones'] as $phone) {
                    $this->addPhone(
                        $phone['areaCode'],
                        $phone['number'],
                        @$phone['cellphone'],
                        @$phone['default'],
                        @$phone['im'],
                        @$phone['otherPurpose'],
                        @$phone['type']
                    );
                }
            }

            /**
             * Contact
             * Need parse 'contacts' key to $extras with array contains one or more contactss
             */
            if (isset($extras['contacts'])) {
                foreach ($extras['contacts'] as $contact) {
                    $this->addContact(
                        $contact['name'],
                        $contact['tag'],
                        $contact['value'],
                        @$contact['label'],
                        @$contact['type']
                    );
                }
            }

            /**
             * Other data
             */
            if (isset($extras['classification'])) {
                $person->setClassification($extras['classification']);
            }

            if (isset($extras['photo'])) {
                $person->setPhoto($extras['photo']);
            }

            if (isset($extras['treatment'])) {
                $person->setTreatment($extras['treatment']);
            }

            if (isset($extras['gender'])) {
                $person->setGender($extras['gender']);
            }

            if (isset($extras['birtDate'])) {
                $person->setBirthDate($extras['birtDate']);
            }

            if (isset($extras['status'])) {
                $person->setStatus($extras['status']);
            }

            if (isset($extras['ipRegistration'])) {
                $person->setIpRegistration($extras['ipRegistration']);
            }

            if (isset($extras['nationality'])) {
                $person->setNationality($extras['nationality']);
            }

            if (isset($extras['description'])) {
                $person->setDescription($extras['description']);
            }
        }

        /**
         * Options
         */

        if ($enablePersist) {
            $this->enablePersist();
        }
        $this->persist($person);
        $this->flush();

        return $person;
    }

    public function find(int $id)
    {
        return $this->om->getRepository(Person::class)->find($id);
    }

    /**
     * Manage a Person
     *
     * @param Person $person
     * @return self
     */
    public function manager(Person $person)
    {
        $this->person = $person;
        return $this;
    }

    /**
     * Check if is managing a Person
     *
     * @return boolean
     */
    public function isManaging()
    {
        return $this->person instanceof Person;
    }

    /**
     * Update a managed Person managed
     * This method is best of ->persist and ->flush of ObjectManager because here call same events and features when
     * update an person
     * @return Person`
     */
    public function update()
    {
        if (!$this->isManaging()) {
            throw new \LogicException('Person is not managing');
        }

        $this->om->persist($this->person);
        $this->flush();
        return $this->person;
    }

    /**
     * Remove a managed Person entity
     *
     * @return boolean
     */
    public function remove()
    {
        // TODO: Implement remove() method.
    }

    /**
     * List Persons
     *
     * @param int $limit
     * @param int $offset
     * @param null $filter
     * @return mixed
     */
    public function list(int $limit, int $offset = 0, $filter = null)
    {
        // TODO: Implement list() method.
    }

    /**
     * Smart Person search
     *
     * @param string $search
     * @return array|Person
     */
    public function search(string $search)
    {
        // TODO: Implement search() method.
    }

    /**
     * Update person classification number
     *
     * @param int $classification
     * @return mixed
     */
    public function updateClassification(int $classification)
    {
        // TODO: Implement updateClassification() method.
    }

    public function createRegistrationOrigin($name, $description, $extra = null)
    {
        // TODO: Implement createRegistrationOrigin() method.
    }

    public function findRegistrationOrigin(int $id)
    {
        // TODO: Implement findRegistrationOrigin() method.
    }

    public function updateRegistrationOrigin(RegistrationOrigin $registrationOrigin)
    {
        // TODO: Implement updateRegistrationOrigin() method.
    }

    public function removeRegistrationOrigin(RegistrationOrigin $registrationOrigin)
    {
        // TODO: Implement removeRegistrationOrigin() method.
    }

    public function listRegistrationOrigins(int $limit = null, int $offset = 0, $filter = null)
    {
        // TODO: Implement listRegistrationOrigins() method.
    }

    /**
     * @param $identifier
     * @param $agentDispatcher
     * @param $expedition
     * @param null $type
     * @return PersonDocument
     * @throws \Exception
     */
    public function addDocument($identifier, $agentDispatcher, $expedition, $type = null)
    {
        $entity = new PersonDocument();
        $entity->setIdentifier($identifier);
        $entity->setAgentDispatcher($agentDispatcher);
        $entity->setExpedition($expedition);
        if ($type instanceof TypePersonDocument) {
            $entity->setType($type);
        } elseif (!empty($type)) {
            $typeId = intval($type);
            $typeEntity = $this->findTypePersonDocument($typeId);
            if (!$typeEntity) {
                throw new \Exception('TypePersonDocument with id ' . $typeId . ' not found');
            }
        }

        if ($this->isManaging()) {
            $this->person->addDocument($entity);
            $entity->setPerson($this->person);
            $this->persist($entity);
        }

        return $entity;
    }

    public function findDocument(int $id)
    {
        return $this->om->getRepository(PersonDocument::class)->find($id);
    }

    public function updateDocument(PersonDocument $document)
    {
        $this->persist($document);
        $this->flush();
        return $document;
    }

    public function removeDocument(PersonDocument $document)
    {
        $this->om->remove($document);
        $this->flush();
        return $this;
    }

    public function listDocuments(int $limit = null, int $offset = 0, $filter = null, $person = null)
    {
        return $this->om->getRepository(PersonDocument::class)->findAllSimple($limit, $offset, @$filter['sortBy'], @$filter['sortDest'], $filter, isset($filter['onlyActive']) ? $filter['active'] : true, $person);
    }

    public function createTypePersonDocument($name, $active = true)
    {
        $entity = new TypePersonDocument();
        $entity->setName($name);
        $entity->setActive($active);
        $this->persist($entity);
        $this->flush();
        return $entity;
    }

    public function findTypePersonDocument(int $id)
    {
        return $this->om->getRepository(TypePersonDocument::class)->find($id);
    }

    public function updateTypePersonDocument(TypePersonDocument $type)
    {
        $this->persist($type);
        $this->flush();
        return $type;
    }

    public function removeTypePersonDocument(TypePersonDocument $type)
    {
        $this->om->remove($type);
        $this->flush();
        return $this;
    }

    public function listTypePersonDocuments(int $limit = null, int $offset = 0, $filter = null)
    {
        return $this->om->getRepository(TypePersonDocument::class)->findAllSimple($limit, $offset, @$filter['sortBy'], @$filter['sortDest'], $filter, isset($filter['onlyActive']) ? $filter['active'] : true);
    }

    public function addAddress($address, $complement = null, $number = null, $district = null, $districtId = null, $zip = null, $city = null, $cityId = null, $state = null, $stateId = null, $otherPurpose = null, $default = false, $latitude = null, $longitude = null, $type = null)
    {
        $entity = new Address();
        $entity->setAddress($address);
        $entity->setComplement($complement);
        $entity->setNumber($number);
        $entity->setDistrict($district);
        $entity->setDistrictId($districtId);
        $entity->setZip($zip);
        $entity->setCity($city);
        $entity->setCityId($cityId);
        $entity->setState($state);
        $entity->setStateId($stateId);
        $entity->setOtherPurpose($otherPurpose);
        $entity->setDefault($default);
        $entity->setLatitude($latitude);
        $entity->setLongitude($longitude);

        if ($type instanceof TypeAddressPurpose) {
            $entity->setPurpose($type);
        } elseif (!empty($type)) {
            $typeId = intval($type);
            $typeEntity = $this->om->getRepository(TypeAddressPurpose::class)->find($typeId);
            if (!$typeEntity) {
                throw new \Exception('TypeAddressPurpose with id ' . $typeId . ' not found');
            }
        }

        if ($this->isManaging()) {
            $this->person->addAddress($entity);
            $entity->setPerson($this->person);
            $this->persist($entity);
        }

        return $entity;
    }

    public function findAddress(int $id)
    {
        return $this->om->getRepository(Address::class)->find($id);
    }

    public function updateAddress(Address $address)
    {
        $this->om->persist($address);
        $this->flush();
        return $address;
    }

    public function removeAddress(Address $address)
    {
        // TODO: Implement removeAddress() method.
    }

    public function listAddresses(int $limit = null, int $offset = 0, $filter = null)
    {
        // TODO: Implement listAddresses() method.
    }

    public function createTypeAddressPurpose($name, $active = true)
    {
        $entity = new TypeAddressPurpose();
        $entity->setName($name);
        $entity->setActive($active);
        $this->persist($entity);
        $this->flush();
        return $entity;
    }

    public function findTypeAddressPurpose(int $id)
    {
        return $this->om->getRepository(TypeAddressPurpose::class)->find($id);
    }

    public function updateTypeAddressPurpose(TypeAddressPurpose $type)
    {
        $this->persist($type);
        $this->flush();
        return $type;
    }

    public function removeTypeAddressPurpose(TypeAddressPurpose $type)
    {
        $this->om->remove($type);
        $this->flush();
        return $this;
    }

    public function listTypeAddressPurposes(int $limit = null, int $offset = 0, $filter = null)
    {
        return $this->om->getRepository(TypeAddressPurpose::class)->findAllSimple($limit, $offset, @$filter['sortBy'], @$filter['sortDest'], $filter, isset($filter['onlyActive']) ? $filter['active'] : true);
    }

    public function addPaper(Person $person, $type, \DateTime $start = null, \DateTime $end = null)
    {
        // TODO: Implement addPaper() method.
    }

    public function findPaper(int $id)
    {
        // TODO: Implement findPaper() method.
    }

    public function endPaper(Paper $paper)
    {
        // TODO: Implement endPaper() method.
    }

    public function updatePaper(Paper $paper)
    {
        // TODO: Implement updatePaper() method.
    }

    public function removePaper(Paper $paper)
    {
        // TODO: Implement removePaper() method.
    }

    public function listPapers(int $limit = null, int $offset = 0, $filter = null)
    {
        // TODO: Implement listPapers() method.
    }

    public function createTypePaper($name, $code = null)
    {
        // TODO: Implement createTypePaper() method.
    }

    public function findTypePaper(int $id)
    {
        // TODO: Implement findTypePaper() method.
    }

    public function updateTypePaper(TypePaper $type)
    {
        // TODO: Implement updateTypePaper() method.
    }

    public function removeTypePaper(TypePaper $type)
    {
        // TODO: Implement removeTypePaper() method.
    }

    public function listTypePapers(int $limit = null, int $offset = 0, $filter = null)
    {
        // TODO: Implement listTypePapers() method.
    }

    public function addNote($annotation, $label = null)
    {
        // TODO: Implement addNote() method.
    }

    public function findNote(int $id)
    {
        // TODO: Implement findNote() method.
    }

    public function updateNote(Note $type)
    {
        // TODO: Implement updateNote() method.
    }

    public function removeNote(Note $type)
    {
        // TODO: Implement removeNote() method.
    }

    public function listNotes(int $limit = null, int $offset = 0, $filter = null)
    {
        // TODO: Implement listNotes() method.
    }

    public function addPhone($areaCode, $number, $cellphone = false, $default = false, $im = null, $otherPurpose = null, $type = null)
    {
        $entity = new ContactPhone();
        $entity->setAreaCode($areaCode);
        $entity->setPhoneNumber($number);
        $entity->setCellphone($cellphone);
        $entity->setDefault($default);
        $entity->setIm($im);
        $entity->setOtherPurpose($otherPurpose);

        if ($type instanceof TypePhonePurpose) {
            $entity->setPurpose($type);
        } elseif (!empty($type)) {
            $typeId = intval($type);
            $typeEntity = $this->om->getRepository(TypePhonePurpose::class)->find($typeId);
            if (!$typeEntity) {
                throw new \Exception('TypePhonePurpose with id ' . $typeId . ' not found');
            }
        }

        if ($this->isManaging()) {
            $this->person->addPhone($entity);
            $entity->setPerson($this->person);
            $this->persist($entity);
        }

        return $entity;
    }

    public function findPhone(int $id)
    {
        return $this->om->getRepository(ContactPhone::class)->find($id);
    }

    public function updatePhone(ContactPhone $phone)
    {
        $this->om->persist($phone);
        $this->flush();
        return $phone;
    }

    public function removePhone(ContactPhone $phone)
    {
        // TODO: Implement removePhone() method.
    }

    public function listPhones(int $limit = null, int $offset = 0, $filter = null)
    {
        // TODO: Implement listPhones() method.
    }

    public function createTypePhonePurpose($name, $active = true)
    {
        $entity = new TypePhonePurpose();
        $entity->setName($name);
        $entity->setActive($active);
        $this->persist($entity);
        $this->flush();
        return $entity;
    }

    public function findTypePhonePurpose(int $id)
    {
        return $this->om->getRepository(TypePhonePurpose::class)->find($id);
    }

    public function updateTypePhonePurpose(TypePhonePurpose $type)
    {
        $this->persist($type);
        $this->flush();
        return $type;
    }

    public function removeTypePhonePurpose(TypePhonePurpose $type)
    {
        $this->om->remove($type);
        $this->flush();
        return $this;
    }

    public function listTypePhonePurposes(int $limit = null, int $offset = 0, $filter = null)
    {
        return $this->om->getRepository(TypePhonePurpose::class)->findAllSimple($limit, $offset, @$filter['sortBy'], @$filter['sortDest'], $filter, isset($filter['onlyActive']) ? $filter['active'] : true);
    }

    public function addEmail($email, $valid = true, $default = false, $otherPurpose = null, $type = null)
    {
        $entity = new ContactEmail();
        $entity->setEmail($email);
        $entity->setValid($valid);
        $entity->setDefault($default);
        $entity->setOtherPurpose($otherPurpose);

        if ($type instanceof TypeEmailPurpose) {
            $entity->setPurpose($type);
        } elseif (!empty($type)) {
            $typeId = intval($type);
            $typeEntity = $this->om->getRepository(TypeEmailPurpose::class)->find($typeId);
            if (!$typeEntity) {
                throw new \Exception('TypeEmailPurpose with id ' . $typeId . ' not found');
            }
        }

        if ($this->isManaging()) {
            $this->person->addEmail($entity);
            $entity->setPerson($this->person);
            $this->persist($entity);
        }

        return $entity;
    }

    public function findEmail(int $id)
    {
        return $this->om->getRepository(ContactEmail::class)->find($id);
    }

    public function updateEmail(ContactEmail $email)
    {
        $this->om->persist($email);
        $this->flush();
        return $email;
    }

    public function removeEmail(ContactEmail $email)
    {
        // TODO: Implement removeEmail() method.
    }

    public function listEmails(int $limit = null, int $offset = 0, $filter = null)
    {
        // TODO: Implement listEmails() method.
    }

    public function createTypeEmailPurpose($name, $active = true)
    {
        $entity = new TypeEmailPurpose();
        $entity->setName($name);
        $entity->setActive($active);
        $this->persist($entity);
        $this->flush();
        return $entity;
    }

    public function findTypeEmailPurpose(int $id)
    {
        return $this->om->getRepository(TypeEmailPurpose::class)->find($id);
    }

    public function updateTypeEmailPurpose(TypeEmailPurpose $type)
    {
        $this->persist($type);
        $this->flush();
        return $type;
    }

    public function removeTypeEmailPurpose(TypeEmailPurpose $type)
    {
        $this->om->remove($type);
        $this->flush();
        return $this;
    }

    public function listTypeEmailPurposes(int $limit = null, int $offset = 0, $filter = null)
    {
        return $this->om->getRepository(TypeEmailPurpose::class)->findAllSimple($limit, $offset, @$filter['sortBy'], @$filter['sortDest'], $filter, isset($filter['onlyActive']) ? $filter['active'] : true);
    }

    public function addContact($name, $tag, $value, $label = null, $type = null)
    {
        $entity = new Contact();
        $entity->setName($name);
        $entity->setTag($tag);
        $entity->setValue($value);
        #$entity->setLabel($label); // TODO: ?

        if ($type instanceof TypeContactPurpose) {
            $entity->setPurpose($type);
        } elseif (!empty($type)) {
            $typeId = intval($type);
            $typeEntity = $this->om->getRepository(TypeContactPurpose::class)->find($typeId);
            if (!$typeEntity) {
                throw new \Exception('TypeContactPurpose with id ' . $typeId . ' not found');
            }
        }

        if ($this->isManaging()) {
            $this->person->addContact($entity);
            $entity->setPerson($this->person);
            $this->persist($entity);
        }

        return $entity;
    }

    public function findContact(int $id)
    {
        return $this->om->getRepository(Contact::class)->find($id);
    }

    public function updateContact(Contact $contact)
    {
        $this->om->persist($contact);
        $this->flush();
        return $contact;
    }

    public function removeContact(Contact $contact)
    {
        // TODO: Implement removeContacts() method.
    }

    public function listContacts(int $limit = null, int $offset = 0, $filter = null)
    {
        // TODO: Implement listContacts() method.
    }

    public function createTypeContactPurpose($name, $active = true)
    {
        $entity = new TypeContactPurpose();
        $entity->setName($name);
        $entity->setActive($active);
        $this->persist($entity);
        $this->flush();
        return $entity;
    }

    public function findTypeContactPurpose(int $id)
    {
        return $this->om->getRepository(TypeContactPurpose::class)->find($id);
    }

    public function updateTypeContactPurpose(TypeContactPurpose $type)
    {
        $this->persist($type);
        $this->flush();
        return $type;
    }

    public function removeTypeContactPurpose(TypeContactPurpose $type)
    {
        $this->om->remove($type);
        $this->flush();
        return $this;
    }

    public function listTypeContactPurposes(int $limit = null, int $offset = 0, $filter = null)
    {
        return $this->om->getRepository(TypeContactPurpose::class)->findAllSimple($limit, $offset, @$filter['sortBy'], @$filter['sortDest'], $filter, isset($filter['onlyActive']) ? $filter['active'] : true);
    }

    public function addExtraField($name, $code = null, $description = null, $required = true)
    {
        // TODO: Implement addExtraField() method.
    }

    public function findExtraField(int $id)
    {
        // TODO: Implement findExtraField() method.
    }

    public function updateExtraField(ExtraField $extraField)
    {
        // TODO: Implement updateExtraField() method.
    }

    public function removeExtraField(ExtraField $extraField)
    {
        // TODO: Implement removeExtraField() method.
    }

    public function listExtraFields(int $limit = null, int $offset = 0, $filter = null)
    {
        // TODO: Implement listExtraFields() method.
    }

    public function addPersonExtraField(ExtraField $field, $value)
    {
        // TODO: Implement addPersonExtraField() method.
    }

    public function findPersonExtraField(int $id)
    {
        // TODO: Implement findPersonExtraField() method.
    }

    public function updatePersonExtraField(PersonExtraField $contact)
    {
        // TODO: Implement updatePersonExtraField() method.
    }

    public function removePersonExtraField(PersonExtraField $contact)
    {
        // TODO: Implement removePersonExtraField() method.
    }

    public function listPersonExtraFields(int $limit = null, int $offset = 0, $filter = null)
    {
        // TODO: Implement listPersonExtraFields() method.
    }

    public function addTag($name, $description = null)
    {
        // TODO: Implement addTag() method.
    }

    public function findTag(int $id)
    {
        // TODO: Implement findTag() method.
    }

    public function updateTag(Tag $tag)
    {
        // TODO: Implement updateTag() method.
    }

    public function removeTag(Tag $tag)
    {
        // TODO: Implement removeTag() method.
    }

    public function listTags(int $limit = null, int $offset = 0, $filter = null)
    {
        // TODO: Implement listTags() method.
    }

    public function communicate()
    {
        // TODO: Implement communicate() method.
    }

    public function registerHistoryCommunicate()
    {
        // TODO: Implement registerHistoryCommunicate() method.
    }

    public function findCommunicate(int $id)
    {
        // TODO: Implement findCommunicate() method.
    }

    public function listCommunicates(int $limit = null, int $offset = 0, $filter = null)
    {
        // TODO: Implement listCommunicates() method.
    }
}
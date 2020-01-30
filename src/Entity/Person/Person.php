<?php
/**
 * Este arquivo é parte do código fonte Uloc
 *
 * (c) Tiago Felipe <tiago@tiagofelipe.com>
 *
 * Para informações completas dos direitos autorais, por favor veja o arquivo LICENSE
 * distribuído junto com o código fonte.
 */

namespace Uloc\ApiBundle\Entity\Person;

use Doctrine\Common\Collections\ArrayCollection;
use Uloc\ApiBundle\Entity\FormEntity;
use Uloc\ApiBundle\Entity\User\User;
use Uloc\ApiBundle\Serializer\ApiRepresentationMetadataInterface;

/**
 * Person
 *
 */
class Person extends FormEntity
{
    const PERSON_INDIVIDUAL = 1;
    const PERSON_COMPANY = 2;

    const TypeString = [
        self::PERSON_INDIVIDUAL => 'Física',
        self::PERSON_COMPANY => 'Jurídica',
    ];

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     * !!!Assert\NotBlank(message="É necessário informar um name válido")
     */
    protected $name;

    /**
     * @var int
     */
    protected $classification = 0;

    /**
     * @var string
     */
    protected $photo;

    /**
     * @var int
     *
     * 0 = Person
     * 1 = Company
     *
     */
    protected $type;

    /**
     * @var string
     *
     * Treatment / Tratamento
     * e.g: Sr/Mr
     */
    protected $treatment;

    /**
     * @var int
     * #Assert\NotBlank(message="É necessário informar um gênero", groups={"PersonFisica"})
     */
    protected $gender;

    /**
     * @var \DateTime
     *
     * #Assert\NotBlank(message="É necessário informar a data de nascimento", groups={"PersonFisica"})
     */
    protected $birthDate;

    /**
     * @var int
     *
     */
    protected $status;

    /**
     * @var string
     *
     */
    protected $ipRegistration;

    /**
     * @var int
     * #Assert\NotBlank(message="É necessário informar a nationality", groups={"PersonFisica"})
     */
    protected $nationality;

    /**
     * @var string
     *
     */
    protected $description;

    /**
     * R
     * Um Person tem Muitos Owners
     */
    protected $owners;

    /**
     * R
     * Um Person tem Muitos PersonsManaged
     */
    protected $personsManaged;

    /**
     * R
     * Muitos Persons tem Um RegistrationOrigin.
     */
    protected $registrationOrigin;

    /**
     * R
     * Um Person tem Muitos Papers
     */
    protected $papers;

    /**
     * R
     * Um SuperPerson tem Muitos SuperPapers
     */
    protected $superPapers;

    /**
     * R
     * Um Person tem Muitos Notes
     */
    protected $notes;

    /**
     * R
     * Um Person tem Muitos ExtraFields
     * !!Assert\Valid
     */
    protected $extraFields;

    /**
     * R
     * Um Person tem Muitos Emails
     * !!Assert\Valid
     * !!Assert\Count(
     *      groups={"registration"},
     *      min = 1,
     *      max = 1,
     *      exactMessage = "É necessário informar um e-mail",
     *      minMessage = "You must specify at least one email",
     *      maxMessage = "You cannot specify more than {{ limit }} emails")
     */
    protected $emails;

    /**
     * R
     * Um Person tem Muitos PhoneNumbers
     * !!Assert\Valid
     * !!Assert\Count(
     *      groups={"PersonFisica", "PersonJuridica"},
     *      min = 1,
     *      exactMessage = "É necessário informar seu phone",
     *      minMessage = "É necessário informar ao menos um phone"
     *      )
     */
    protected $phoneNumbers;

    /**
     * R
     * Um Person tem Muitos ContactExtra
     * !!Assert\Valid
     */
    protected $contactExtra;

    /**
     * R
     * Um Person tem Muitos PersonDocument
     * !!Assert\Valid
     */
    protected $documents;

    /**
     * R
     * Um Person tem Muitos Addresses
     * !!Assert\Valid
     * !!Assert\Count(
     *      groups={"Default"},
     *      min = 1,
     *      max = 1,
     *      exactMessage = "É necessário informar seu endereço")
     */
    protected $addresses;

    /**
     * R
     * Um Person tem Muitos Tags
     */
    protected $tags;

    /**
     * R
     * Um Criador tem Muitos TagsCreated
     */
    protected $tagsCreated;

    /**
     * R
     * Um Person tem Muitos Users
     */
    protected $users;

    /**
     * R
     * Um Person tem Muitos CommunicationHistory
     */
    protected $communicationHistory;

    public function __construct()
    {
        $this->owners = new ArrayCollection();
        $this->personsManaged = new ArrayCollection();
        $this->papers = new ArrayCollection();
        $this->superPapers = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->extraFields = new ArrayCollection();
        $this->emails = new ArrayCollection();
        $this->phoneNumbers = new ArrayCollection();
        $this->contactExtra = new ArrayCollection();
        $this->addresses = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->tagsCreated = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->communicationHistory = new ArrayCollection();
        $this->documents = new ArrayCollection();

        $this->setDateAdded(new \DateTime());
        $this->ipRegistration = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1'; //TODO: Verificar
        $this->status = 1;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return static
     */
    public function setName($name)
    {
        $this->name = ucwords(mb_strtolower(trim($name), 'UTF-8'));

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function getFirstName()
    {
        $name = explode(' ', $this->name);
        return ucfirst(mb_strtolower(trim($name[0]), 'UTF-8'));
    }

    /**
     * Set classification
     *
     * @param integer $classification
     *
     * @return static
     */
    public function setClassification($classification)
    {
        $this->classification = $classification;

        return $this;
    }

    /**
     * Get classification
     *
     * @return int
     */
    public function getClassification()
    {
        return $this->classification;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return static
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return static
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getCommunicationHistory()
    {
        return $this->communicationHistory;
    }

    /**
     * @param CommunicationHistory $communicationHistory
     * @return static
     */
    public function addCommunicationHistory(CommunicationHistory $communicationHistory)
    {
        $this->communicationHistory[] = $communicationHistory;
        return $this;
    }

    /**
     * @param CommunicationHistory $communicationHistory
     * @return static
     */
    public function removeCommunicationHistory(CommunicationHistory $communicationHistory)
    {
        $this->communicationHistory->removeElement($communicationHistory);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param User $user
     * @return static
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;
        return $this;
    }

    /**
     * @param User $user
     * @return static
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTagsCreated()
    {
        return $this->tagsCreated;
    }

    /**
     * @param Tag $tagCreated
     * @return static
     */
    public function addTagCreated(Tag $tagCreated)
    {
        $this->tagsCreated[] = $tagCreated;
        return $this;
    }

    /**
     * @param Tag $tagCreated
     * @return static
     */
    public function removeTagCreated(Tag $tagCreated)
    {
        $this->tagsCreated->removeElement($tagCreated);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param Tag $tag
     * @return static
     */
    public function addTag(Tag $tag)
    {
        $this->tags[] = $tag;
        return $this;
    }

    /**
     * @param Tag $tag
     * @return static
     */
    public function removeTag(Tag $tag)
    {
        $this->tags->removeElement($tag);
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * @param Address $address
     * @return static
     */
    public function addAddress(Address $address)
    {
        $address->setPerson($this);
        $this->addresses[] = $address;
        return $this;
    }

    /**
     * @param Address $address
     * @return static
     */
    public function removeAddress(Address $address)
    {
        $this->addresses->removeElement($address);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * @param PersonDocument $document
     * @return static
     */
    public function addDocument(PersonDocument $document)
    {
        $this->documents[] = $document;
        return $this;
    }

    /**
     * @param PersonDocument $document
     * @return static
     */
    public function removeDocument(PersonDocument $document)
    {
        $this->documents->removeElement($document);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContactExtra()
    {
        return $this->contactExtra;
    }

    /**
     * @param ContactExtra $contactExtra
     * @return static
     */
    public function addContactExtra(ContactExtra $contactExtra)
    {
        $this->contactExtra[] = $contactExtra;
        return $this;
    }

    /**
     * @param ContactExtra $contactExtra
     * @return static
     */
    public function removeContactExtra(ContactExtra $contactExtra)
    {
        $this->contactExtra->removeElement($contactExtra);
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPhoneNumbers()
    {
        return $this->phoneNumbers;
    }

    /**
     * @param ContactPhone $phone
     * @return static
     */
    public function addPhone(ContactPhone $phone)
    {
        $phone->setPerson($this);
        $this->phoneNumbers[] = $phone;
        return $this;
    }

    /**
     * @param ContactPhone $phone
     * @return static
     */
    public function removePhone(ContactPhone $phone)
    {
        $this->phoneNumbers->removeElement($phone);
        return $this;
    }

    /**
     * @return ArrayCollection|ContactEmail
     */
    public function getEmails()
    {
        return $this->emails;
    }

    /**
     * @param ContactEmail $email
     * @return static
     */
    public function addEmail(ContactEmail $email)
    {
        $email->setPerson($this);
        $this->emails[] = $email;
        return $this;
    }

    /**
     * @param ContactEmail $email
     * @return static
     */
    public function removeEmail(ContactEmail $email)
    {
        $this->emails->removeElement($email);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param Note $note
     * @return static
     */
    public function addNote(Note $note)
    {
        $this->notes[] = $note;
        return $this;
    }

    /**
     * @param Note $note
     * @return static
     */
    public function removeNote(Note $note)
    {
        $this->notes->removeElement($note);
        return $this;
    }

    /**
     * @return ArrayCollection|PersonExtraField
     */
    public function getExtraFields()
    {
        return $this->extraFields;
    }

    /**
     * @param PersonExtraField $extraField
     * @return static
     */
    public function addExtraField(PersonExtraField $extraField)
    {
        $extraField->setPerson($this);
        $this->extraFields[] = $extraField;
        return $this;
    }

    /**
     * @param PersonExtraField $extraField
     * @return static
     */
    public function removeExtraField(PersonExtraField $extraField)
    {
        $this->extraFields->removeElement($extraField);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSuperPapers()
    {
        return $this->superPapers;
    }

    /**
     * Add superPaper
     *
     * @param \Uloc\ApiBundle\Entity\Person\Paper $superPaper
     *
     * @return static
     */
    public function addSuperPaper(\Uloc\ApiBundle\Entity\Person\Paper $superPaper)
    {
        $this->superPapers[] = $superPaper;

        return $this;
    }

    /**
     * Remove superPaper
     *
     * @param \Uloc\ApiBundle\Entity\Person\Paper $superPaper
     */
    public function removeSuperPaper(\Uloc\ApiBundle\Entity\Person\Paper $superPaper)
    {
        $this->superPapers->removeElement($superPaper);
    }

    /**
     * @return mixed
     */
    public function getPapers()
    {
        return $this->papers;
    }

    /**
     * @param Paper $paper
     */
    public function addPaper(Paper $paper)
    {
        $this->papers[] = $paper;
    }

    /**
     * @return mixed
     */
    public function getRegistrationOrigin()
    {
        return $this->registrationOrigin;
    }

    /**
     * @param RegistrationOrigin $registrationOrigin
     */
    public function setRegistrationOrigin(RegistrationOrigin $registrationOrigin)
    {
        $this->registrationOrigin = $registrationOrigin;
    }

    /**
     * @return mixed
     */
    public function getOwners()
    {
        return $this->owners;
    }

    /**
     * @param Owner $owner
     */
    public function addOwner(Owner $owner)
    {
        $this->owners[] = $owner;
    }

    /**
     * Remove owner
     *
     * @param \Uloc\ApiBundle\Entity\Person\Owner $owner
     */
    public function removeOwner(\Uloc\ApiBundle\Entity\Person\Owner $owner)
    {
        $this->owners->removeElement($owner);
    }

    /**
     * @return mixed
     */
    public function getPersonsManaged()
    {
        return $this->personsManaged;
    }

    /**
     * @param Owner $personManaged
     */
    public function addPersonManaged(Owner $personManaged)
    {
        $this->personsManaged[] = $personManaged;
    }

    /**
     * Add personsManaged
     *
     * @param Owner $personsManaged
     *
     * @return static
     */
    public function addPersonsManaged(Owner $personsManaged)
    {
        $this->personsManaged[] = $personsManaged;

        return $this;
    }

    /**
     * Remove personsManaged
     *
     * @param Owner $personsManaged
     */
    public function removePersonsManaged(Owner $personsManaged)
    {
        $this->personsManaged->removeElement($personsManaged);
    }

    /**
     * Remove paper
     *
     * @param Paper $paper
     */
    public function removePaper(Paper $paper)
    {
        $this->papers->removeElement($paper);
    }

    /**
     * Add tagsCreated
     *
     * @param \Uloc\ApiBundle\Entity\Person\Tag $tagsCreated
     *
     * @return static
     */
    public function addTagsCreated(\Uloc\ApiBundle\Entity\Person\Tag $tagsCreated)
    {
        $this->tagsCreated[] = $tagsCreated;

        return $this;
    }

    /**
     * Remove tagsCreated
     *
     * @param \Uloc\ApiBundle\Entity\Person\Tag $tagsCreated
     */
    public function removeTagsCreated(\Uloc\ApiBundle\Entity\Person\Tag $tagsCreated)
    {
        $this->tagsCreated->removeElement($tagsCreated);
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    public function getTypeString()
    {
        if (isset(self::TypeString[$this->type])) {
            return self::TypeString[$this->type];
        }
        return 'n/d';
    }

    /**
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getTreatment()
    {
        return $this->treatment;
    }

    /**
     * @param string $treatment
     */
    public function setTreatment($treatment)
    {
        $this->treatment = $treatment;
    }

    /**
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param int $gender
     */
    public function setGender(int $gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param \DateTime $birthDate
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return string
     */
    public function getIpRegistration()
    {
        return $this->ipRegistration;
    }

    /**
     * @param string $ipRegistration
     */
    public function setIpRegistration($ipRegistration)
    {
        $this->ipRegistration = $ipRegistration;
    }

    /**
     * @return int
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * @param int $nationality
     */
    public function setNationality($nationality)
    {
        $this->nationality = $nationality;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    public static function loadApiRepresentation(ApiRepresentationMetadataInterface $representation)
    {
    }

}

<?php

namespace Uloc\ApiBundle\Entity\Person;

/**
 * NoteHistory
 *
 */
class NoteHistory
{
    /**
     * @var int
     *
     */
    private $id;

    /**
     * @var \DateTime
     *
     */
    private $date;

    /**
     * @var \stdClass
     *
     */
    private $oldData;

    /**
     * Muitos Historicos tem Um Note.
     */
    private $note;

    /**
     */
    private $person;

    /**
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param Note $note
     */
    public function setNote(Note $note)
    {
        $this->note = $note;
    }

    /**
     * @return mixed
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param Person $person
     */
    public function setPerson(Person $person)
    {
        $this->person = $person;
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return static
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set oldData
     *
     * @param \stdClass $oldData
     *
     * @return static
     */
    public function setOldData($oldData)
    {
        $this->oldData = $oldData;

        return $this;
    }

    /**
     * Get oldData
     *
     * @return \stdClass
     */
    public function getOldData()
    {
        return $this->oldData;
    }
}

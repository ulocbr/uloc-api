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

/**
 * Uma person pode ter vários papéis.
 *
 * Exemplos:
 *
 * - Tiago (Person) é (Paper) CEO (TypePaper) da Wtis (Person/superPaper)
 * - João é Representante Comercial de Pedro
 *
 */
class Paper
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
    private $start;

    /**
     * Se end for preenchido quer dizer que este paper não é mais exercido por esta person
     *
     * @var \DateTime
     *
     */
    private $end;

    /**
     * Muitos Papers tem Um Person.
     */
    private $person;

    /**
     * Muitos SuperPapers tem Um Person.
     */
    private $superPerson;

    /**
     * Muitos Papers tem Um TypePaper.
     */
    private $typePaper;

    /**
     * @return mixed
     */
    public function getTypePaper()
    {
        return $this->typePaper;
    }

    /**
     * @param TypePaper $typePaper
     */
    public function setTypePaper(TypePaper $typePaper)
    {
        $this->typePaper = $typePaper;
    }

    /**
     * @return mixed
     */
    public function getSuperPerson()
    {
        return $this->superPerson;
    }

    /**
     * @param Person $superPerson
     */
    public function setSuperPerson(Person $superPerson)
    {
        $this->superPerson = $superPerson;
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
     * Set start
     *
     * @param \DateTime $start
     *
     * @return static
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set end
     *
     * @param \DateTime $end
     *
     * @return static
     */
    public function setEnd($end)
    {
        $this->end = $end;

        return $this;
    }

    /**
     * Get end
     *
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }
}

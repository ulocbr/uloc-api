<?php

namespace Uloc\ApiBundle\Entity\Person;

use Doctrine\Common\Collections\ArrayCollection;
use Uloc\ApiBundle\Entity\FormEntity;
use Uloc\ApiBundle\Serializer\ApiRepresentationMetadataInterface;

/**
 * Note
 *
 */
class Note extends FormEntity
{
    /**
     * @var int
     *
     */
    private $id;

    /**
     * @var string
     *
     */
    private $note;

    /**
     * @var string
     *
     */
    private $label;

    /**
     * Muitos Anotacoes tem Um Person.
     */
    private $person;

    /**
     * Um Anotacoes tem Muitos History
     */
    private $history;

    /**
     * Um Anotacoes tem Muitos Comentarios
     */
    private $comments;

    public function __construct()
    {
        $this->history = new ArrayCollection();
        $this->comments = new ArrayCollection();
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
     * @return mixed
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * @param NoteHistory $history
     */
    public function addHistory(NoteHistory $history)
    {
        $this->history[] = $history;
    }

    /**
     * Remove history
     *
     * @param \Uloc\ApiBundle\Entity\Person\NoteHistory $history
     */
    public function removeHistory(\Uloc\ApiBundle\Entity\Person\NoteHistory $history)
    {
        $this->history->removeElement($history);
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
     * Set note
     *
     * @param string $note
     *
     * @return Note
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return Note
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return ArrayCollection|NoteComment
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param NoteComment $comments
     */
    public function addComment(NoteComment $comment): void
    {
        $this->comments[] = $comment;
    }

    static function loadApiRepresentation(ApiRepresentationMetadataInterface $representation)
    {
        // TODO: Implement loadApiRepresentation() method.
    }
}

<?php

namespace Uloc\ApiBundle\Entity\Person;

use Doctrine\Common\Collections\ArrayCollection;
use Uloc\ApiBundle\Entity\FormEntity;
use Uloc\ApiBundle\Serializer\ApiRepresentationMetadataInterface;

/**
 * Note
 *
 */
class NoteComment extends FormEntity
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
    private $comment;

    /**
     * @var string
     *
     */
    private $label;

    /**
     * Many NoteComment's is relacted to an Note.
     */
    private $note;

    public function __construct()
    {
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
     * @param Note $note
     *
     * @return self
     */
    public function setNote(Note $note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return Note
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
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     */
    public function setComment($comment): void
    {
        $this->comment = $comment;
    }

    static function loadApiRepresentation(ApiRepresentationMetadataInterface $representation)
    {
        // TODO: Implement loadApiRepresentation() method.
    }
}

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
use Doctrine\ORM\Mapping as ORM;
use Uloc\ApiBundle\Serializer\ApiRepresentationMetadataInterface;

/**
 * TypePaper
 *
 */
class TypePaper extends TypePurpose
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
    private $description;

    /**
     * Um TipoPaper tem Muitos Papers
     */
    private $papers;

    public function __construct()
    {
        $this->papers = new ArrayCollection();
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return static
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Remove paper
     *
     * @param \Uloc\ApiBundle\Entity\Person\Paper $paper
     */
    public function removePaper(\Uloc\ApiBundle\Entity\Person\Paper $paper)
    {
        $this->papers->removeElement($paper);
    }

    static function loadApiRepresentation(ApiRepresentationMetadataInterface $representation)
    {
        // TODO: Implement loadApiRepresentation() method.
    }
}

<?php
/**
 * Este arquivo é parte do código fonte Uloc
 *
 * (c) Tiago Felipe <tiago@tiagofelipe.com>
 *
 * Para informações completas dos direitos autorais, por favor veja o arquivo LICENSE
 * distribuído junto com o código fonte.
 */

namespace Uloc\ApiBundle\Entity;

use Uloc\ApiBundle\Entity\User\User;
use Uloc\ApiBundle\Helpers\Sluggable;
use Uloc\ApiBundle\Serializer\ApiRepresentationMetadataInterface;

/**
 * FormEntity
 *
 * Fornece métodos e propriedades típicas para um CRUD que envolva operações de usuários
 *
 * Class FormEntity.
 */
abstract class FormEntity extends CommonEntity
{

    /**
     * @var integer
     */
    private $oldId;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var bool
     */
    private $active = true;

    /**
     * @var null|\DateTime
     */
    private $createdAt = null;

    /**
     * @var null|int
     */
    private $createdBy;

    /**
     * @var null|string
     */
    private $createdByUser;

    /**
     * @var null|string
     */
    private $createdByName;

    /**
     * @var null|\DateTime
     */
    private $dateModified;

    /**
     * var null|int.
     */
    private $modifiedBy;

    /**
     * @var null|string
     */
    private $modifiedByUser;

    /**
     * @var null|\DateTime
     */
    private $checkedOut;

    /**
     * @var null|int
     */
    private $checkedOutBy;

    /**
     * @var null|string
     */
    private $checkedOutByUser;

    /**
     * @var null|int
     */
    private $order = 0;

    /**
     * @var null|boolean
     */
    private $deleted = false;

    /**
     * @var bool
     */
    protected $new = false;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Clear dates on clone.
     */
    public function __clone()
    {
        $this->createdAt = null;
        $this->dateModified = null;
        $this->checkedOut = null;
        $this->active = false;
        $this->deleted = false;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set dateModified.
     *
     * @param \DateTime $dateModified
     *
     * @return $this
     */
    public function setDateModified($dateModified)
    {
        $this->dateModified = $dateModified;

        return $this;
    }

    /**
     * Get dateModified.
     *
     * @return \DateTime
     */
    public function getDateModified()
    {
        return $this->dateModified;
    }

    /**
     * Set checkedOut.
     *
     * @param \DateTime $checkedOut
     *
     * @return $this
     */
    public function setCheckedOut($checkedOut)
    {
        $this->checkedOut = $checkedOut;

        return $this;
    }

    /**
     * Get checkedOut.
     *
     * @return \DateTime
     */
    public function getCheckedOut()
    {
        return $this->checkedOut;
    }

    /**
     * Set createdBy.
     *
     * @param User $createdBy
     *
     * @return $this
     */
    public function setCreatedBy($createdBy = null)
    {
        if ($createdBy != null && !$createdBy instanceof User) {
            $this->createdBy = $createdBy;
        } else {
            $this->createdBy = ($createdBy != null) ? $createdBy->getId() : null;
            if ($createdBy != null) {
                $this->createdByUser = $createdBy->getUsername();
            }
        }

        return $this;
    }

    /**
     * Get createdBy.
     *
     * @return int
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set modifiedBy.
     *
     * @param User $modifiedBy
     *
     * @return mixed
     */
    public function setModifiedBy($modifiedBy = null)
    {
        if ($modifiedBy != null && !$modifiedBy instanceof User) {
            $this->modifiedBy = $modifiedBy;
        } else {
            $this->modifiedBy = ($modifiedBy != null) ? $modifiedBy->getId() : null;

            if ($modifiedBy != null) {
                $this->modifiedByUser = $modifiedBy->getUsername();
            }
        }

        return $this;
    }

    /**
     * Get modifiedBy.
     *
     * @return int
     */
    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }

    /**
     * Set checkedOutBy.
     *
     * @param User $checkedOutBy
     *
     * @return mixed
     */
    public function setCheckedOutBy($checkedOutBy = null)
    {
        if ($checkedOutBy != null && !$checkedOutBy instanceof User) {
            $this->checkedOutBy = $checkedOutBy;
        } else {
            $this->checkedOutBy = ($checkedOutBy != null) ? $checkedOutBy->getId() : null;

            if ($checkedOutBy != null) {
                $this->checkedOutByUser = $checkedOutBy->getUsername();
            }
        }

        return $this;
    }

    /**
     * Get checkedOutBy.
     *
     * @return int
     */
    public function getCheckedOutBy()
    {
        return $this->checkedOutBy;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return $this
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        if ($this->new) {
            return true;
        }

        $id = $this->getId();

        return (empty($id)) ? true : false;
    }

    /**
     * Set this entity as new in case it has to be saved prior to the events.
     */
    public function setNew()
    {
        $this->new = true;
    }

    /**
     * @return string
     */
    public function getCheckedOutByUser()
    {
        return $this->checkedOutByUser;
    }

    /**
     * @return string
     */
    public function getCreatedByUser()
    {
        return $this->createdByUser;
    }

    /**
     * @return string
     */
    public function getModifiedByUser()
    {
        return $this->modifiedByUser;
    }

    /**
     * @param mixed $createdByUser
     */
    public function setCreatedByUser($createdByUser)
    {
        $this->createdByUser = $createdByUser;
    }

    /**
     * @param mixed $modifiedByUser
     */
    public function setModifiedByUser($modifiedByUser)
    {
        $this->modifiedByUser = $modifiedByUser;
    }

    /**
     * @param mixed $checkedOutByUser
     */
    public function setCheckedOutByUser($checkedOutByUser)
    {
        $this->checkedOutByUser = $checkedOutByUser;
    }

    /**
     * @return int|null
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int|null $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @param string $slug
     */
    public function setSlugAuto($stringToSlug)
    {
        $this->slug = Sluggable::slugify($stringToSlug);
    }

    /**
     * @return bool|null
     */
    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    /**
     * @param bool|null $deleted
     */
    public function setDeleted(?bool $deleted): void
    {
        $this->deleted = $deleted;
    }

    /**
     * @return string|null
     */
    public function getCreatedByName()
    {
        return $this->createdByName;
    }

    /**
     * @param string|null $createdByName
     */
    public function setCreatedByName($createdByName): void
    {
        $this->createdByName = $createdByName;
    }

    /**
     * @return int
     */
    public function getOldId(): ?int
    {
        return $this->oldId;
    }

    /**
     * @param int $oldId
     */
    public function setOldId(?int $oldId): void
    {
        $this->oldId = $oldId;
    }

    static $serializeApi = [
        'slug',
        'active',
        'createdAt',
        'createdBy',
        'createdByUser',
        'createdByName',
        'dateModified',
        'modifiedBy',
        'modifiedByUser',
        'checkedOut',
        'checkedOutBy',
        'checkedOutByUser',
        'order',
        'deleted'
    ];

    static function loadApiRepresentation(ApiRepresentationMetadataInterface $representation)
    {
        $representation
            ->setGroup('all')
            ->addProperties(self::$serializeApi);
    }

}

<?php

namespace Uloc\ApiBundle\Entity;

/**
 * ApiToken
 *
 * Entidade responsável por armazenar os tokens permanentes no sistema
 *
 */
class ApiToken
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
    private $token;

    /**
     * @var \stdClass
     *
     */
    private $permission;

    /**
     * @var string
     *
     */
    private $notes;

    /**
     * @var \DateTime
     *
     */
    private $createdAt;

    private $expires;

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
     * Set token
     *
     * @param string $token
     *
     * @return ApiToken
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set permission
     *
     * @param \stdClass $permission
     *
     * @return ApiToken
     */
    public function setPermission($permission)
    {
        $this->permission = $permission;

        return $this;
    }

    /**
     * Get permission
     *
     * @return \stdClass
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * Set notes
     *
     * @param string $notes
     *
     * @return ApiToken
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ApiToken
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return mixed
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * @param mixed $expires
     * @return self
     */
    public function setExpires($expires)
    {
        $this->expires = $expires;
        return $this;
    }
}

<?php

namespace Uloc\ApiBundle\Entity;

use Uloc\ApiBundle\Entity\User\User;

/**
 * ApiToken
 *
 * Entidade responsável por armazenar os tokens permanentes no sistema
 *
 */
class ApiToken extends FormEntity
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
     * @var User
     *
     */
    private $user;

    /**
     * @var \array
     *
     */
    private $permission;

    /**
     * @var string
     *
     */
    private $notes;

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
     * @param \array $permission
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
     * @return array
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

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

}

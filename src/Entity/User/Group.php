<?php

namespace Uloc\ApiBundle\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Uloc\ApiBundle\Model\GroupInterface;

/**
 * Group
 *
 */
class Group implements GroupInterface
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
    private $name;

    /**
     * @var mixed
     *
     */
    private $roles;

    private $acl;

    /**
     * Many Groups have Many Users.
     */
    private $users;

    /**
     * One Groups have Default Many Users.
     */
    private $usersDefault;

    /**
     * Group constructor.
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->usersDefault = new ArrayCollection();
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
     * @return string
     */
    public function setName($name)
    {
        $this->name = $name;

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

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param mixed $user
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;
    }

    /**
     * Remove user
     *
     * @param User $user
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @param string $role
     */
    public function addRole($role)
    {
        if (!$this->hasRole($role)) {
            $this->roles[] = strtoupper($role);
        }
    }

    /**
     * @param string $role
     *
     * @return bool
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->roles, true);
    }

    /**
     * @param string $role
     *
     * @return static
     */
    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAcl()
    {
        return $this->acl;
    }

    /**
     * @param mixed $roles
     */
    public function setAcl(array $acl)
    {
        $this->acl = $acl;
    }

    /**
     * @param string $acl
     */
    public function addAcl($acl)
    {
        if (!$this->hasAcl($acl)) {
            $this->acl[] = strtoupper($acl);
        }
    }

    /**
     * @param string $acl
     *
     * @return bool
     */
    public function hasAcl($acl)
    {
        return in_array(strtoupper($acl), $this->acl, true);
    }

    /**
     * @param string $acl
     *
     * @return static
     */
    public function removeAcl($acl)
    {
        if (false !== $key = array_search(strtoupper($acl), $this->acl, true)) {
            unset($this->acl[$key]);
            $this->acl = array_values($this->acl);
        }

        return $this;
    }

    public function getUsersDefault()
    {
        return $this->usersDefault;
    }

    public function setUsersDefault($usersDefault)
    {
        $this->usersDefault = $usersDefault;
    }

    
}

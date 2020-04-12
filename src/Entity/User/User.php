<?php
/**
 * Este arquivo é parte do código fonte Uloc
 *
 * (c) Tiago Felipe <tiago@tiagofelipe.com>
 *
 * Para informações completas dos direitos autorais, por favor veja o arquivo LICENSE
 * distribuído junto com o código fonte.
 */

namespace Uloc\ApiBundle\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;
use Uloc\ApiBundle\Entity\FormEntity;
use Uloc\ApiBundle\Entity\Person\Person;
use Uloc\ApiBundle\Model\GroupableInterface;
use Uloc\ApiBundle\Model\GroupInterface;
use Uloc\ApiBundle\Model\UserInterface;
use Symfony\Component\Security\Core\User\UserInterface as DefaultUserInterface;
use Uloc\ApiBundle\Serializer\ApiRepresentationMetadataInterface;

class User extends FormEntity implements UserInterface, GroupableInterface
{
    protected $id;

    protected $name; // For Person

    protected $username;

    protected $email;

    protected $password;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @var string
     */
    protected $plainPassword;

    /**
     * The salt to use for hashing.
     * No used for api token
     * @var string
     */
    protected $salt;

    protected $roles;

    protected $acl;

    /**
     * @var \DateTime|null
     */
    protected $lastLogin;

    /**
     * Random string sent to the user email address in order to verify it.
     * @var string|null
     */
    protected $confirmationToken;

    /**
     * @var \DateTime|null
     */
    protected $passwordRequestedAt;

    /**
     * @var bool
     */
    protected $enabled;

    /**
     * Provider status types for users in application.
     * @var integer
     */
    protected $status;

    /**
     * Many Users have Many Groups.
     */
    protected $groups;

    /**
     * Muitos Usuarios tem Uma Person.
     */
    private $person;

    public function __construct($username = null, $password = null, $salt = null, array $roles = [], $status = 0, array $acl = null)
    {
        parent::__construct();
        $this->enabled = false;
        $this->username = $username;
        $this->password = $password;
        $this->salt = $salt;
        $this->roles = empty($roles) ? [] : $roles;
        $this->acl = empty($acl) ? [] : $acl;;
        $this->groups = new ArrayCollection();
        $this->status = $status;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles()
    {
        $roles = $this->roles;

        foreach ($this->getGroups() as $group) {
            $roles = array_merge($roles, $group->getRoles());
        }

        // we need to make sure to have at least one role
        $roles[] = static::ROLE_DEFAULT;

        return array_unique($roles);
    }

    /**
     * {@inheritdoc}
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    /**
     * {@inheritdoc}
     */
    public function setRoles(array $roles)
    {
        $this->roles = array();

        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addRole($role)
    {
        if(empty($role)) return $this;

        $role = strtoupper($role);
        if ($role === static::ROLE_DEFAULT) {
            return $this;
        }

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    public function getSalt()
    {
        return $this->salt; // TODO: API need this?
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * {@inheritdoc}
     */
    public function getGroups()
    {
        return $this->groups ?: $this->groups = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function addGroup(GroupInterface $group)
    {
        if (!$this->getGroups()->contains($group)) {
            $this->getGroups()->add($group);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeGroup(GroupInterface $group)
    {
        if ($this->getGroups()->contains($group)) {
            $this->getGroups()->removeElement($group);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isEqualTo(DefaultUserInterface $user)
    {
        if (!$user instanceof self) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->salt !== $user->getSalt()) { // TODO: API whith token need this?
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getGroupNames()
    {
        $names = array();
        foreach ($this->getGroups() as $group) {
            $names[] = $group->getName();
        }

        return $names;
    }

    /**
     * {@inheritdoc}
     */
    public function hasGroup($name)
    {
        return in_array($name, $this->getGroupNames());
    }

    /**
     * {@inheritdoc}
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * {@inheritdoc}
     */
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuperAdmin()
    {
        return $this->hasRole(static::ROLE_SUPER_ADMIN);
    }

    /**
     * {@inheritdoc}
     */
    public function setSuperAdmin($boolean)
    {
        if (true === $boolean) {
            $this->addRole(static::ROLE_SUPER_ADMIN);
        } else {
            $this->removeRole(static::ROLE_SUPER_ADMIN);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setEnabled($boolean)
    {
        $this->enabled = (bool)$boolean;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * {@inheritdoc}
     */
    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setPasswordRequestedAt(\DateTime $date = null)
    {
        $this->passwordRequestedAt = $date;

        return $this;
    }

    /**
     * Gets the timestamp that the user requested a password reset.
     *
     * @return null|\DateTime
     */
    public function getPasswordRequestedAt()
    {
        return $this->passwordRequestedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function isPasswordRequestNonExpired($ttl)
    {
        return $this->getPasswordRequestedAt() instanceof \DateTime &&
            $this->getPasswordRequestedAt()->getTimestamp() + $ttl > time();
    }

    /**
     * {@inheritdoc}
     */
    public function setLastLogin(\DateTime $time = null)
    {
        $this->lastLogin = $time;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastLogin(): ?\DateTime
    {
        return $this->lastLogin;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param mixed $person
     */
    public function setPerson(Person $person)
    {
        $this->person = $person;
    }

    public function addAcl($acl)
    {
        if(empty($acl)) return $this;
        if (!in_array($acl, $this->acl, true)) {
            $this->acl[] = $acl;
        }

        return $this;
    }

    public function removeAcl($acl)
    {
        if (false !== $key = array_search(strtoupper($acl), $this->acl, true)) {
            unset($this->acl[$key]);
            $this->acl = array_values($this->acl);
        }

        return $this;
    }

    public function setAcl(array $acl)
    {
        $this->acl = array();

        foreach ($acl as $_acl) {
            $this->addAcl($_acl);
        }

        return $this;
    }

    public function getAcl()
    {
        return $this->acl;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName($name): self
    {
        $this->name = $name;
        return $this;
    }


    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            $this->salt,
            $this->acl,
            $this->enabled,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $data = array_values($data); // TODO: Check if it is necessary to unset some values

        list(
            $this->id,
            $this->username,
            $this->email,
            $this->password,
            $this->salt,
            $this->acl,
            $this->enabled,
            ) = $data;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getUsername();
    }

    static function loadApiRepresentation(ApiRepresentationMetadataInterface $representation)
    {
        parent::loadApiRepresentation($representation);
        $public = [
            'id',
            'username',
            'email',
            'roles',
            'acl',
            'person' => ['id', 'name'],
            'status'
        ];

        $admin = [
            'id',
            'username',
            'email',
            'roles',
            'acl',
            'lastLogin',
            'enabled',
            'groups',
            'person' => ['id', 'name'],
            'status'
        ];
        $representation
            ->setGroup('public')
            ->addProperties(
                $public
            )
            ->setGroup('admin')
            ->addProperties(
                array_merge($public, $admin)
            )->build();
    }


}

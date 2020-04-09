<?php

namespace Uloc\ApiBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Uloc\ApiBundle\Entity\User\User;
use Uloc\ApiBundle\Manager\Model\CustomManager;
use Uloc\ApiBundle\Services\JWT\Encoder\JWTEncoderInterface;

class UserManager extends CustomManager implements UserManagerInterface
{
    private $encoder;
    private $passwordEncoder;

    public function __construct(ObjectManager $om, JWTEncoderInterface $encoder, UserPasswordEncoderInterface $passwordEncoder = null)
    {
        $this->encoder = $encoder;
        $this->passwordEncoder = $passwordEncoder;
        parent::__construct($om);
    }


    /* @var User */
    private $user;

    public function create(string $name, string $username, string $password, bool $active = true, array $extras = null, array $options = null)
    {
        $user = new User();
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setActive($active);

        // self-manager
        $this->manager($user);
        $enablePersist = false;
        if ($this->persistEnabled) {
            $this->disablePersist();
            $enablePersist = true;
        }

        /**
         * Set Extra Data
         * Possibilites: Address, Emails, Phones, ContactExtra
         */
        if (is_array($extras)) {

        }

        /**
         * Options
         */

        if ($enablePersist) {
            $this->enablePersist();
        }
        $this->persist($user);
        $this->flush();

        return $user;
    }

    public function find(int $id)
    {
        // TODO: Implement find() method.
    }

    /**
     * Manage a User
     *
     * @param User $user
     * @return self
     */
    public function manager(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Check if is managing a User
     *
     * @return boolean
     */
    public function isManaging()
    {
        return $this->user instanceof User;
    }

    /**
     * Update a managed User managed
     * This method is best of ->persist and ->flush of ObjectManager because here call same events and features when
     * update an user
     * @return User
     */
    public function update()
    {
        // TODO: Implement update() method.
    }

    /**
     * Remove a managed User entity
     *
     * @return boolean
     */
    public function remove()
    {
        // TODO: Implement remove() method.
    }

    /**
     * List Users
     *
     * @param int $limit
     * @param int $offset
     * @param null $filter
     * @return mixed
     */
    public function list(int $limit, int $offset = 0, $filter = null)
    {
        // TODO: Implement list() method.
    }

    /**
     * Smart User search
     *
     * @param string $search
     * @return array|User
     */
    public function search(string $search)
    {
        // TODO: Implement search() method.
    }

    public function findByUsername(string $username)
    {
        return $this->om
            ->getRepository(User::class)
            ->loadUserByUsername($username, false);
    }

    public function isPasswordValid($password)
    {
        if ($this->passwordEncoder) {
            return $this->passwordEncoder->isPasswordValid($this->user, $password);
        }
        return strcmp($this->user->getPassword(), $password) === 0;
    }

    public function generateToken(int $expiration = 86400) // // 1 day expiration
    {
        if (!$this->encoder) {
            throw new \Exception('Encoder is not avaliable in UserManager');
        }
        return $this->encoder->encode([
            'username' => $this->user->getUsername(),
            'exp' => time() + $expiration // 1 day expiration
        ]);
    }

}
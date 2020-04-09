<?php
/**
 * Created by PhpStorm.
 * User: Tiago
 * Date: 08/04/2020
 * Time: 16:41
 */

namespace Uloc\ApiBundle\Manager;

use Doctrine\Common\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Uloc\ApiBundle\Entity\User\User;
use Uloc\ApiBundle\Services\JWT\Encoder\JWTEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

interface UserManagerInterface
{

    public function __construct(ObjectManager $om, JWTEncoderInterface $encoder, UserPasswordEncoderInterface $passwordEncoder = null, EventDispatcherInterface $eventDispatcher = null);

    /**
     * Create an new User
     *
     * @param string $name
     * @param string $username
     * @param string $password
     * @param bool $active
     * @param array $extras
     * @param array $options
     * @return User
     *
     *
     */
    public function create(string $name, string $username, string $pasword, bool $active = true, array $extras = null, array $options = null);

    /**
     * Find and return an User entity
     *
     * @param int $id
     * @return User|null
     */
    public function find(int $id);

    /**
     * Manage a User
     *
     * @param User $user
     * @return self
     */
    public function manager(User $user);

    /**
     * Check if is managing a User
     *
     * @return boolean
     */
    public function isManaging();

    /**
     * Update a managed User managed
     * This method is best of ->persist and ->flush of ObjectManager because here call same events and features when
     * update an user
     * @return User
     */
    public function update();

    /**
     * Remove a managed User entity
     *
     * @return boolean
     */
    public function remove();

    /**
     * List Users
     *
     * @param int $limit
     * @param int $offset
     * @param null $filter
     * @return mixed
     */
    public function list(int $limit, int $offset = 0, $filter = null);

    /**
     * Smart User search
     *
     * @param string $search
     * @return array|User
     */
    public function search(string $search);

    /**
     * Smart User search
     *
     * @param string $username
     * @return User|null
     */
    public function findByUsername(string $username);

    /**
     * @param $password
     * @return Boolean
     */
    public function isPasswordValid($password);

    /**
     * @param int $expiration
     * @return string
     * @throws \Exception
     */
    public function generateToken(int $expiration);

    /**
     * @return array
     */
    public function getUserContent();

    public function dispatch(Event $event, $eventName);

}
<?php

namespace Uloc\ApiBundle\Manager;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Uloc\ApiBundle\Entity\Person\Person;
use Uloc\ApiBundle\Entity\User\User;
use Uloc\ApiBundle\Event\UlocApiBundleEvents;
use Uloc\ApiBundle\Event\UserNewTokenEvent;
use Uloc\ApiBundle\Manager\Model\CustomManager;
use Uloc\ApiBundle\Services\JWT\Encoder\JWTEncoderInterface;

class UserManager extends CustomManager implements UserManagerInterface
{
    private $encoder;
    private $passwordEncoder;
    private $eventDispatcher;
    /* @var PersonManager */
    private $personManager;

    public function __construct(ObjectManager $om, JWTEncoderInterface $encoder, PersonManagerInterface $personManager, UserPasswordEncoderInterface $passwordEncoder = null, EventDispatcherInterface $eventDispatcher = null)
    {
        $this->encoder = $encoder;
        $this->passwordEncoder = $passwordEncoder;
        $this->eventDispatcher = $eventDispatcher;
        $this->personManager = $personManager;
        parent::__construct($om);
    }


    /* @var User */
    private $user;

    public function create(string $name, string $username, string $email, string $password, bool $active = true, array $extras = null, array $options = null, $createPerson = true, Person $person = null)
    {
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($password);
        if ($this->passwordEncoder) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
        }
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

        if (null !== $person && $person instanceof Person) {
            $user->setPerson($person);
        } elseif ($createPerson) {
            $person = $this->personManager->disablePersist()->create($name);
            $user->setPerson($person);
            $this->persist($person);
        }
        $this->persist($user);
        $this->flush();

        return $user;
    }

    public function find(int $id)
    {
        return $this->om->getRepository(User::class)->find($id);
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
     * @param null|array $filters
     * @param null|string $format
     * @param null|boolean $hydrate
     * @return mixed
     */
    public function list(int $limit, int $offset = 0, array $filters = null, $format = null, $hydrate = null)
    {
        return $this->om->getRepository(User::class)->findAllSimple($limit, $offset, $filters, $hydrate);
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

    public function getUserContent()
    {
        $response = [
            "id" => $this->user->getId(),
            "email" => $this->user->getEmail(),
            "name" => $this->user->getPerson() ? $this->user->getPerson()->getName() : $this->user->getUsername(),
            "image" => 'https://www.gravatar.com/avatar/' . trim(strtolower(md5($this->user->getEmail()))),

        ];

        $data = [
            'response' => $response,
            'user' => $this->user
        ];

        $event = new UserNewTokenEvent($data);
        $this->dispatch($event, UlocApiBundleEvents::EVENT_USER_NEW_TOKEN);

        return $event->getData()['response'];
    }

    public function dispatch(Event $event, $eventName)
    {
        if ($this->eventDispatcher) {
            $this->eventDispatcher->dispatch($event, $eventName);
        }
    }

}
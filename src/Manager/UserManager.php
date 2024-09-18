<?php

namespace Uloc\ApiBundle\Manager;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Uloc\ApiBundle\Entity\App\GlobalConfig;
use Uloc\ApiBundle\Entity\AuthSecurity;
use Uloc\ApiBundle\Entity\AuthSecurityIp;
use Uloc\ApiBundle\Entity\Person\Person;
use Uloc\ApiBundle\Entity\User\User;
use Uloc\ApiBundle\Event\UlocApiBundleEvents;
use Uloc\ApiBundle\Event\User2FAEvent;
use Uloc\ApiBundle\Event\UserNewTokenEvent;
use Uloc\ApiBundle\Helpers\Utils;
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

    public function create(string $name, string $username, string $email, $password = null, bool $active = true, array $extras = null, array $options = null, $createPerson = true, Person $person = null)
    {
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        if (empty($password)) {
            $password = $this->generatePassword();
        }
        $user->setPlainPassword($password);
        if ($this->passwordEncoder) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
        } else {
            $user->setPassword($user->getPlainPassword());
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
         * Possibilites: Roles, ACL, Address, Emails, Phones, Contacts
         */
        if (is_array($extras)) {
            if (isset($extras['roles'])) {
                $user->setRoles($extras['roles']);
            }

            if (isset($extras['acl'])) {
                $user->setAcl($extras['acl']);
            }
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
     * Check if is managing a User (TODO: Create exception for this)
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
     * @return self
     */
    public function update()
    {
        $this->om->persist($this->user);
        $this->om->flush();
        return $this;
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
        return $this->om->getRepository(User::class)->findAllUserSimple($limit, $offset, $filters, $hydrate);
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

    public function findUserByDocument(string $document)
    {
        return $this->om
            ->getRepository(User::class)
            ->loadUserByPersonDocument($document, false);
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
            'id' => $this->user->getId(),
            'username' => $this->user->getUsername(),
            'client' => $_SERVER['USER_CLIENT'] ?? null, // TODO: Isso não deve ficar aqui.
            'exp' => time() + $expiration // 1 day expiration
        ]);
    }

    public function getUserContent()
    {
        $response = [
            "id" => $this->user->getId(),
            "email" => $this->user->getEmail(),
            "username" => $this->user->getUsername(),
            "name" => $this->user->getPerson() ? $this->user->getPerson()->getName() : $this->user->getUsername(),
            "roles" => $this->user->getRoles(),
            "acl" => $this->user->getAcl(),
            "config" => $this->user->getConfig(),
            // "image" => 'https://www.gravatar.com/avatar/' . trim(strtolower(md5($this->user->getEmail()))),
            "image" => $this->user->getPerson() ? $this->user->getPerson()->getPhoto() : 'https://www.gravatar.com/avatar/' . trim(strtolower(md5($this->user->getEmail()))), // @DEPRECATED
            "photo" => $this->user->getPerson() ? $this->user->getPerson()->getPhoto() : 'https://www.gravatar.com/avatar/' . trim(strtolower(md5($this->user->getEmail()))),

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

    public function generatePassword()
    {
        $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
        return $password = substr(str_shuffle($data), 0, 6);
    }

    public function redefinePassword($password = null)
    {
        $password = null === $password ? $this->generatePassword() : $password;
        $user = $this->user;
        $user->setPlainPassword($password);
        if ($this->passwordEncoder) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
        } else {
            $user->setPassword($user->getPlainPassword());
        }
        $this->persist($user);
        $this->flush();
        return $password;
    }

    public function getSecurityConfig()
    {
        $auth2FA = $this->om->getRepository(GlobalConfig::class)->findOneBy([
            'name' => 'security.2FA'
        ]);

        $auth2FARoles = $this->om->getRepository(GlobalConfig::class)->findOneBy([
            'name' => 'security.2FA.roles'
        ]);

        $validateIP = $this->om->getRepository(GlobalConfig::class)->findOneBy([
            'name' => 'security.validateIp'
        ]);

        return [
            'security.2FA' => $auth2FA ? $auth2FA->getValue() : null,
            'security.2FA.roles' => $auth2FARoles ? $auth2FARoles->getValue() : null,
            'security.validateIp' => $validateIP ? $validateIP->getValue() : null,
        ];
    }

    public function checkIp()
    {
        $ip = Utils::get_client_ip_env();

        $buscaIp = $this->om->getRepository(AuthSecurityIp::class)->findOneBy([
            'ip' => $ip
        ]);

        if (!$buscaIp) {
            return false;
        }

        if ($buscaIp->isBlock()) {
            throw new \Exception('Service Unavailable', 503);
        }

        if ($buscaIp->isValid()) {
            return true;
        }

        return $buscaIp;
    }

    public function start2FA(User $user, $config)
    {
        $check2f = $this->om->getRepository(AuthSecurity::class)->createQueryBuilder('a')
            ->where('a.user = :user')
            ->andWhere('a.expires > :agora')
            ->setParameter('user', $user->getId())
            ->setParameter('agora', (new \DateTime())->format('Y-m-d H:i:s'))
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();

        $new = false;
        if (!$check2f) {
            $new = true;
            $check2f = new AuthSecurity();
            $check2f->setToken(md5(uniqid()));
            $code = rand(0, 999999);
            $code = sprintf('%06d', $code);
            $check2f->setCode($code);
            $check2f->setMethod($config);
            $check2f->setExpires((new \DateTime())->modify('+10 minutes'));
            $check2f->setUser($user);
            $this->om->persist($check2f);
            $this->om->flush();
        }

        if ($this->eventDispatcher) {
            $new && $this->eventDispatcher->dispatch(new User2FAEvent($check2f), 'security.2FA');
        }

        return $check2f;
    }

    public function validate2FA($token, $code)
    {
        $check2f = $this->om->getRepository(AuthSecurity::class)->createQueryBuilder('a')
            ->where('a.token = :token')
            ->andWhere('a.expires > :agora')
            ->andWhere('a.code = :code')
            ->setParameter('token', $token)
            ->setParameter('agora', (new \DateTime())->format('Y-m-d H:i:s'))
            ->setParameter('code', $code)
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();

        if (!$check2f) {
            return false;
        }

        $ip = $this->checkIp();
        if (!$ip) {
            $ip = new AuthSecurityIp();
            $ip->setIp(Utils::get_client_ip_env());
            $ip->setValid(true);
            $ip->setBlock(false);
            $ip->setDate(new \DateTime());
            $ip->setExpires((new \DateTime())->modify('+10 days'));
            $this->om->persist($ip);
            $this->om->flush();
        }

        return true;
    }

    public function persist2FA($entity)
    {
        $this->om->persist($entity);
        $this->om->flush();
    }

}
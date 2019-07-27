<?php

namespace Uloc\ApiBundle\Command;

use Doctrine\ORM\EntityManagerInterface; /* TODO: Configurar a dependência do ObjectManager como serviço e preconfigurado no Extension */
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Uloc\ApiBundle\Entity\User\User;

class UlocUserCreateCommand extends Command
{
    protected static $defaultName = 'uloc:user:create';

    private $em;
    private $encoder;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $this->em = $em;
        $this->encoder = $encoder;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Criar um novo usuário')
            ->addArgument('usuario', InputArgument::REQUIRED, 'Username')
            ->addArgument('senha', InputArgument::REQUIRED, 'Password')
            ->addOption('roles', null, InputOption::VALUE_REQUIRED, 'Roles do usuário, separando-os por vírgula. Ex: ROLE_USER,ROLEINTRANET')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('usuario');
        $plainPassword = $input->getArgument('senha');
        $em = $this->em;
        $findUser = $em->getRepository(User::class)->findOneBy(['username' => $username]);
        if($findUser){
            $io->error("Já existe um usuário com o name ".$username."!");
            return;
        }

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($username . '@wtis.com.br');
        $password = $this->encoder
            ->encodePassword($user, $plainPassword);
        $user->setPassword($password);

        $user->setRoles(['ROLE_USER']);

        if ($input->getOption('roles')) {
            $roles = explode(',', $input->getOption('roles'));
            $user->setRoles($roles);
        }

        $em->persist($user);
        $em->flush();

        $io->title('Usuário '.$username. ' criado com sucesso!');
        $io->text('Email: '. $user->getEmail() );
        $io->text('Senha: '. $plainPassword );
        $io->newLine();
    }

}

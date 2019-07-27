<?php

namespace Uloc\ApiBundle\Command;

use Doctrine\ORM\EntityManagerInterface; /* TODO: Configurar a dependência do ObjectManager como serviço e preconfigurado no Extension */
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Uloc\ApiBundle\Entity\User\User;
use Uloc\ApiBundle\Services\JWT\Encoder\JWTEncoderInterface;

class UlocTokenGetCommand extends Command
{
    protected static $defaultName = 'uloc:token:get';

    private $em;
    private $encoder;

    public function __construct(EntityManagerInterface $em, JWTEncoderInterface $encoder)
    {
        $this->em = $em;
        $this->encoder = $encoder;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Criar um novo token para um usuário')
            ->addArgument('usuario', InputArgument::REQUIRED, 'Username')
            ->addOption('exp', null, InputOption::VALUE_REQUIRED, 'Expiração do token em horas. Exemplo: 27H')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $username = $input->getArgument('usuario');
        $em = $this->em;
        $findUser = $em->getRepository(User::class)->findOneBy(['username' => $username]);
        if(!$findUser){
            $io->error("Usuário ".$username." não encontrado!");
            return;
        }

        $exp = 3600; //1 hora

        if ($input->getOption('exp')) {
            $exp = (intval( preg_replace('/\D/', '', $input->getOption('exp')) ) * 60) * 60;
        }
        $tempoEmHoras = ($exp/60)/60;

        $token = $this->encoder
            ->encode([
                'username' => $username,
                'exp' => time() + $exp
            ]);

        $io->title('Token para '.$username);
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        $date = strftime("%d de %B de %G %H:%M", time() + $exp);
        $io->text('Expira em '. $date . ' ('. $tempoEmHoras .' hora'. ($tempoEmHoras>1?'s':'').')' );
        $io->newLine();
        $output->writeln('<info>'.$token.'</info>');
        /*$output->writeln([
            'Token para '.$username,
            'Expiração: '. ($exp/60)/60 .' horas',
            '====================================',
            '<info>'.$token.'</info>',
        ]);*/
    }

}

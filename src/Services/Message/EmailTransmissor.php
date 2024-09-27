<?php
/**
 * Este arquivo é parte do código fonte Uloc
 *
 * (c) Tiago Felipe <tiago@tiagofelipe.com>
 *
 * Para informações completas dos direitos autorais, por favor veja o arquivo LICENSE
 * distribuído junto com o código fonte.
 */

namespace Uloc\ApiBundle\Services\Message;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Validator\Validation;
use Uloc\ApiBundle\Services\Config\ConfigServiceInterface;
use Uloc\ApiBundle\Services\Log\LogInterface;

class EmailTransmissor extends MessageTransmissor
{

    private $mailer;
    private $transport;

    public function __construct(ConfigServiceInterface $configService, LogInterface $logger, MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        parent::__construct($configService, $logger);
    }

    public function transmit()
    {
        $message = $this->getMessage();

        $clientTransport = $this->configService->getAppConfig('mail-transport.email');

        $config = $message->getConfig();
        if ($this->transport) {
        } elseif ($config) {
            if (isset($config['transport_inline'])) {
                $this->transport = $transport = Transport::fromDsn($config['transport']);
                $this->mailer = new Mailer($transport);
            } elseif (isset($config['transport']) || $clientTransport) { // @TODO: Por que $config['transport']? Avaliar retirada
                $transport = $clientTransport;
                if (empty($transport)) {
                    throw new \Exception(sprintf('Transport %s not found in software configuration. Need to exists %s config with transport DSN', $transport, 'mail-transport.' . $config['transport']));
                }
                $this->transport = $transport = Transport::fromDsn($transport);
                $this->mailer = new Mailer($transport);
            }
        }
        $email = (new Email())
            ->from(new Address($message->getSender(), $message->getSenderName()))
            ->to(new Address($message->getRecipient(), $message->getRecipientName()))
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            ->priority(Email::PRIORITY_HIGH)
            ->subject($message->getSubject())
            ->text($message->getMessageText())
            ->html($message->getMessage());

        if ($message->getCcs()) {
            if (is_array($message->getCcs())) {
                foreach ($message->getCcs() as $cc) {
                    $email->addCc($cc);
                }
            } else {
                $email->cc($message->getCcs());
            }
        }

        // Config to copy
        if ($message->getSession() !== 'mkt') {
            $emailGlobalCc = $this->configService->getAppConfig('email.default.cc');
            if (!empty($emailGlobalCc)) {
                $validator = Validation::createValidator();
                $violations = $validator->validate($emailGlobalCc, [
                    new \Symfony\Component\Validator\Constraints\Email()
                ]);

                if (0 === count($violations)) {
                    $email->addBcc($emailGlobalCc);
                }
            }
        }

        if ($message->getBccs()) {
            $email->bcc($message->getBccs());
        }

        if ($message->getReplyTo()) {
            $email->replyTo($message->getReplyTo());
        }

        if ($message->getPriority()) {
            $email->priority($message->getPriority());
        }

        if ($message->getAttachments()) {
            foreach ($message->getAttachments() as $attachment) {
                $email->attach($attachment->getFile(), $attachment->getFilename());
            }
        }

        try {
            $this->mailer->send($email);
            return true;
        } catch (TransportExceptionInterface | \Exception $e) {
            $this->logger->error($e->getMessage());
            if ('cli' === PHP_SAPI) {
                var_dump($e->getMessage());
            }
            if (MessageServiceFactory::$interceptErrors) {
                throw $e;
            }
            return false;
            // some error prevented the email sending; display an
            // error message or try to resend the message
        }
    }

    public function parseConnection($connection) {

        if (!empty($connection['dsn'])) {
            $this->transport = $transport = Transport::fromDsn($connection['dsn']);
        } else {
            if (empty($connection['hostname'])) {
                throw new \Exception('Ausência do hostname nas configurações da conexão');
            }
            if (empty($connection['username'])) {
                throw new \Exception('Ausência do username nas configurações da conexão');
            }
            $tls = false;
            if (!empty($connection['encryption'])) {
                $tls = $connection['encryption'] === 'tls';
            }
            $this->transport = $transport = (new  Transport\Smtp\EsmtpTransport($connection['hostname'], $connection['port'] ?? 465, $tls))
                ->setUsername($connection['username'])
                ->setPassword($connection['password'] ?? null)
            ;
            $transport->addAuthenticator(new Transport\Smtp\Auth\LoginAuthenticator());
        }

        $this->mailer = new Mailer($transport);
    }

    public function getName()
    {
        return 'email';
    }
}
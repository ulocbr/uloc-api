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
use Uloc\ApiBundle\Services\Config\ConfigServiceInterface;
use Uloc\ApiBundle\Services\Log\LogInterface;

class EmailTransmissor extends MessageTransmissor
{

    private $mailer;

    public function __construct(ConfigServiceInterface $configService, LogInterface $logger, MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        parent::__construct($configService, $logger);
    }

    public function transmit()
    {
        $message = $this->getMessage();

        $config = $message->getConfig();
        if ($config) {
            if (isset($config['transport_inline'])) {
                $transport = Transport::fromDsn($config['transport']);
                $this->mailer = new Mailer($transport);
            } elseif (isset($config['transport'])) {
                $transport = $this->configService->getAppConfig('mail-transport.' . $config['transport']);
                if (empty($transport)) {
                    throw new \Exception(sprintf('Transport %s not found in software configuration. Need to exists %s config with transport DSN', $transport, 'mail-transport.' . $config['transport']));
                }
                $transport = Transport::fromDsn($transport);
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
            $email->cc($message->getCcs());
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
            return false;
            // some error prevented the email sending; display an
            // error message or try to resend the message
        }
    }

    public function getName()
    {
        return 'email';
    }
}
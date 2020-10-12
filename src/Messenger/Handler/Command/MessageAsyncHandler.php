<?php

namespace Uloc\ApiBundle\Messenger\Handler\Command;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Uloc\ApiBundle\Entity\MessageService\Message;
use Uloc\ApiBundle\Services\Log\LogInterface;
use Uloc\ApiBundle\Services\Message\MessageServiceFactory;
use Uloc\ApiBundle\Services\Message\MessageServiceInterface;
use Uloc\ApiBundle\Services\Message\MessageTransmissor;

class MessageAsyncHandler implements MessageHandlerInterface
{


    private $om;
    private $logger;
    private $messageServiceFactory;

    public function __construct(ObjectManager $om, LogInterface $logger, MessageServiceFactory $messageServiceFactory)
    {
        $this->om = $om;
        $this->logger = $logger;
        $this->messageServiceFactory = $messageServiceFactory;
    }

    public function __invoke(Message $message)
    {
        $transmissor = $this->messageServiceFactory->parseMessage($message);
        // dd($message->getId());
        $id = $message->getId();
        $message = $this->om->getRepository(Message::class)->find($id);

        if (!$message) {
            $this->logger->critical(sprintf('[messenger] Fail to send email from message id %s. Message not found in database', $id));
            return;
        }
        if ($transmissor->transmit()) {
            $message->setStatus(MessageServiceInterface::STATUS_SENDED);
            $this->om->persist($message);
            $this->om->flush();
        }
        //dd($message);
        #var_dump($message);
    }
}
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

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Messenger\MessageBusInterface;
use Uloc\ApiBundle\Entity\MessageService\Message;
use Uloc\ApiBundle\Entity\MessageService\MessageAttachment;
use Uloc\ApiBundle\Services\Log\LogInterface;

/**
 * @TODO: Service to generate link to define Receiver and Readed props
 * Responsável por construir uma menssagem
 * @author Tiago Felipe
 * @version 0.0.1
 */
class MessageServiceFactory
{

    const SENDER_QUEUE = 'queue';
    const SENDER_INSTANTLY = 'instantly';

    /**
     * @var string
     * queue - Fila
     * instantly - Envia imediatamente
     */
    private $senderType = self::SENDER_QUEUE;

    private $om;
    private $logger;
    private $transmissor;
    private $bus;

    public function __construct(ObjectManager $om, LogInterface $logger, MessageTransmissor $transmissor, MessageBusInterface $bus = null)
    {
        $this->om = $om;
        $this->logger = $logger;
        $this->transmissor = $transmissor;
        $this->bus = $bus;
    }

    public function setSenderType($type)
    {
        $this->senderType = $type;
        return $this;
    }

    /**
     * Cria uma nova instancia de MessageService e adiciona o transmissor de acordo o
     * tipo e adiciona a message na fila para envio.
     *
     * O retorno é uma instancia do objeto Message.
     *
     * @param string $transmissorAlias Transmissor responsável pelo envio/tipo da message. Exemplo: SMS, Email, Whatsapp, Voice
     * @param string $senderName
     * @param string $sender
     * @param string $recipientName
     * @param string $recipient
     * @param string $subject
     * @param string $text
     * @param string $pureText
     * @param array $config
     * @param array $extra
     *
     * @return Message se tudo ocorrer com sucesso ou NULL em caso de erro
     * @throws \Exception
     */
    public function queue($transmissorAlias, $senderName, $sender, $recipientName, $recipient, $subject, $text, $pureText = null, $config = [], $extra = [])
    {
        try {
            if (!$this->transmissor->getTransmissor($transmissorAlias)) {
                throw new \Exception('Transsmissor ' . $transmissorAlias . ' not exists.');
            }

            $message = new Message();
            $message->setType($transmissorAlias);
            $message->setDateRegistry(new \DateTime());
            $message->setSenderName($senderName);
            $message->setSender($sender);
            $message->setRecipientName($recipientName);
            $message->setRecipient($recipient);
            $message->setSubject($subject);
            $message->setMessage($text);
            $message->setMessageText($pureText);
            $message->setStatus(MessageServiceInterface::STATUS_CREATED);
            $message->setExtra($extra);
            // TODO: CC, BCC, ReplyTo

            if (isset($config['attachments']) && is_array($config['attachments'])) {
                // @TODO: Em caso de mensagem assíncrona, como será tratado os anexos?
                foreach ($config['attachments'] as $_attachment) {
                    if(!($_attachment instanceof MessageAttachment)){
                        // ... @todo
                        /*$attachment = new MessageAttachment();
                        $attachment->setFile($_attachment);
                        $attachment->setFilename();*/
                    } else{
                        $attachment = $_attachment;
                    }
                    $message->addAttachment($attachment);
                    $attachment->setMessage($message);
                    $this->om->persist($attachment);
                }
            }

            if (isset($config['priority'])) {
                $message->setPriority(intval($config['priority']));
            }

            $transmissor = $this->parseMessage($message);

            $this->om->persist($message);
            $this->om->flush();

            // TODO: Não está funcionando
            if (isset($config['schedule'])) {
                if (!($config['schedule'] instanceof \DateTime)) {
                    throw new \Exception('$extra[\'schedule\'] need an instance of \DateTime');
                }
                $message->setScheduleFor($config['schedule']);
            }

            if (isset($config['transport'])) {
                $message->setConfig(array_merge_recursive($message->getConfig(), ['transport' => $config['transport']]));
            }

            if ($this->senderType === self::SENDER_INSTANTLY) {
                if ($transmissor->transmit()) {
                    $this->setSended($message);
                    $this->om->persist($message);
                    $this->om->flush();
                }
            } else {
                if ($this->bus) {
                    $messageLight = clone $message;
                    /*$messageLight->setMessage(null);
                    $messageLight->setMessageText(null);
                    $messageLight->clearAttachments();
                    $messageLight->clearLogs();*/ // TODO: TEST LARGE EMAILS
                    $this->bus->dispatch($messageLight);
                }
            }
        } catch (\Exception $e) {
            $this->logger->error('Fail to queue/transmit message. ' . get_class($transmissor) . '. Error: ' . $e->getMessage() . ' - ' . $e->getTraceAsString(), ['exception' => $e]);
            throw $e;
        }
        return $message;
    }

    /**
     * @param integer $id
     * @return \Uloc\ApiBundle\Entity\MessageService\Message
     */
    public function find($id)
    {
        return $this->om->getRepository(Message::class)->find($id);
    }

    public function parseMessage(Message $message)
    {
        $transmissor = $this->transmissor->getTransmissor($message->getType());
        $message->setType($transmissor->getName());
        $transmissor->setMessage($message);

        return $transmissor;
    }

    public function setSended(Message $message)
    {
        $message->setStatus(MessageServiceInterface::STATUS_SENDED);
        $message->setDateSend(new \DateTime());
    }

}
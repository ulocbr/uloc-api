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

    public static $interceptErrors = false;
    public static $errors = [];

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
     * @param string $session
     *
     * @return Message se tudo ocorrer com sucesso ou NULL em caso de erro
     * @throws \Exception
     */
    public function queue($transmissorAlias, $senderName, $sender, $recipientName, $recipient, $subject, $text, $pureText = null, $config = [], $extra = [], $session = 'default')
    {
        try {
            if (!$this->transmissor->getTransmissor($transmissorAlias)) {
                throw new \Exception('Transsmissor ' . $transmissorAlias . ' not exists.');
            }

            if (is_array($recipient)) {
                if (count($recipient) > 1) {
                    $ccs = array_slice($recipient, 0, count($recipient));
                    if (isset($extra['ccs']) && is_array($extra['ccs'])) {
                        $extra['ccs'] = array_merge($extra['ccs'], $ccs);
                    } else {
                        $extra['ccs'] = $ccs;
                    }
                }
                $recipient = $recipient[0];
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
            $message->setSession($session);

            if (!empty($config['personId'])) {
                $message->setPersonId($config['personId']);
            }

            // TODO: BCC, ReplyTo

            if (!empty($config['referEntity'])) {
                $message->setReferEntity($config['referEntity']);
            }
            if (!empty($config['referEntityId'])) {
                $message->setReferEntityId($config['referEntityId']);
            }

            if (isset($config['attachments']) && is_array($config['attachments'])) {
                // @TODO: Em caso de mensagem assíncrona, como será tratado os anexos?
                foreach ($config['attachments'] as $_attachment) {
                    if (!($_attachment instanceof MessageAttachment)) {
                        // ... @todo
                        /*$attachment = new MessageAttachment();
                        $attachment->setFile($_attachment);
                        $attachment->setFilename();*/
                    } else {
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

            if (!empty($config['sender']['connection'])) {
                $transmissor->parseConnection($config['sender']['connection']);
            }

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

            if (isset($extra['ccs']) && is_array($extra['ccs'])) {
                $message->setCcs($extra['ccs']);
            }

            // Pixel
            if (!isset($config['disablePixel'])) {
                if ($transmissorAlias === 'email') {
                    $clientInstance = '';
                    if (isset($_ENV['CLIENT_DOMAIN'])) {
                        $clientInstance = 'client=' . $_ENV['CLIENT_DOMAIN'] . '&';
                    }
                    $text .= '<img src="' . $_ENV['PIXEL_ENDPOINT'] . '?' . $clientInstance . 'id=' . $message->getId() . '">';
                    $message->setMessage($text);
                    $this->om->persist($message);
                    $this->om->flush();
                }
            }

            if ($this->senderType === self::SENDER_INSTANTLY) {
                if ($transmissor->transmit()) {
                    $this->setSended($message);
                    $this->om->persist($message);
                    $this->om->flush();
                } else {
                    $message->setStatus(MessageServiceInterface::STATUS_ERROR);
                    if (!empty(self::$errors)) {
                        $extra = $message->getExtra();
                        if (!is_array($extra)) {
                            $extra = [];
                        }
                        $extra['error'] = end(self::$errors);
                        $message->setExtra($extra);
                    }
                    $this->om->persist($message);
                    $this->om->flush();
                }
            } else {
                if ($this->bus) {
                    $messageLight = clone $message;
                    $messageLight->setMessage(null);
                    $messageLight->setMessageText(null);
                    $messageLight->clearAttachments();
                    $messageLight->clearLogs();
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
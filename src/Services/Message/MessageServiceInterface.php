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

/**
 * O sistema deve prover uma arquitetura e funcionalidades para um serviço de
 * envio de message.
 * @author Tiago Felipe
 * @version 0.0.1
 */
interface MessageServiceInterface
{

    const STATUS_CREATED = 0;
    const STATUS_SENDED = 1;
    const STATUS_RECEIVED = 2;
    const STATUS_READED = 3;
    const STATUS_ERROR = 99;

    /*
     * TODO: Criar doc dos métodos
     */
    public function setTransmissor();
    public function getTransmissor();
    public function getSenderName();
    public function getSender();
    public function getRecipientName();
    public function getRecipient();
    public function getCcs();
    public function getBccs();
    public function getReplyTo();
    public function getPriority();
    public function getSubject();
    public function getMessage();
    public function getMessageText();
    public function getConfig(): ?array;
    public function getId();

}
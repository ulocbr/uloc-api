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
    /*
     * TODO: Criar doc dos métodos
     */
    public function setTransmissor();
    public function getTransmissor();
    public function getSenderName();
    public function getSender();
    public function getRecipientName();
    public function getRecipient();
    public function getMessage();
    public function getId();

}
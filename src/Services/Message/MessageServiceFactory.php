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
use Doctrine\ORM\EntityManager;
use Uloc\ApiBundle\Entity\MessageService\Message;

/**
 * Responsável por construir uma message
 * @author Tiago Felipe
 * @version 0.0.1
 */
class MessageServiceFactory
{

    public function __construct(EntityManager $em)
    {
    }

    /**
     * Cria uma nova instancia de MessageService e adiciona o transmissor de acordo o
     * tipo e adiciona a message na fila para envio.
     *
     * O retorno é o ID da message na fila. Com este ID é possível fazer checagens de
     * status e outros dados.
     *
     * @param MessageServiceTransmissorInterface $transmissor  Transmissor responsável pelo envio/tipo da message. Exemplo: SMS, Email, Whatsapp, Voice
     * @param string    nameRemetente
     * @param string    remetente
     * @param string    nameDestinatario
     * @param string    destinatario
     *
     * @return Message se tudo ocorrer com sucesso ou NULL em caso de erro
     */
    public function queue(MessageServiceTransmissorInterface $transmissor, $nameRemetente, $remetente, $nameDestinatario, $destinatario)
    {
        //TODO: Transmit uma message. Código abaixo é somente de exemplo
        $transmissor->transmit();
        return new Message();
    }

    /**
     * @param id
     * @return \Uloc\ApiBundle\Entity\MessageService\Message
     */
    public function find($id)
    {
    }

}
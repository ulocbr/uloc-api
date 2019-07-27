<?php
/**
 * Este arquivo é parte do código fonte Uloc
 *
 * (c) Tiago Felipe <tiago@tiagofelipe.com>
 *
 * Para informações completas dos direitos autorais, por favor veja o arquivo LICENSE
 * distribuído junto com o código fonte.
 */

namespace Uloc\ApiBundle\Services\Log;

use Uloc\ApiBundle\Model\UserInterface;

/**
 * Interface para prover os métodos de um Logger padrão
 *
 */
interface LogInterface
{

    /**
     * Registra um log.
     *
     * @param object usuario       Usuário responsável pela operação
     * @param string mensagem      Mensagem de identificação resumida do log. Exemplo:  Alterou um cadastro de uma pessoa
     * @param string entidade      Entidade que sofreu a alteração. Exemplo:  PessoaEntity
     * @param string acao          Ação realizada: new * update  * delete
     * @param string contexto      Um objeto contendo informações complementares do registro de log.
     * @param object oldObject     Um array contendo informações sobre a entidade antes de sofrer alterações. Se for uma nova entidade este array será vazio.
     * @param object newObject     Um array com a entidade alterada ou criada.
     */
    public function log(UserInterface $user, $mensagem, $entidade, $acao, $contexto, $oldObject, $newObject);

}
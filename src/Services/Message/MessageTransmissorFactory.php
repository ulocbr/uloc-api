<?php
/**
 * Este arquivo é parte do código fonte Uloc
 *
 * (c) Tiago Felipe <tiago@tiagofelipe.com>
 *
 * Para informações completas dos direitos autorais, por favor veja o arquivo LICENSE
 * distribuído junto com o código fonte.
 */

/**
 * NÃO USADO POR ENQUANTO
 */

namespace Uloc\ApiBundle\Services\Message;

class MessageTransmissorFactory
{
    public static function injectDependencies () {
        $arg_list = func_get_args();
        for ($i = 0; $i < func_num_args(); $i++) {
            dump("Argument $i is: " . $arg_list[$i] . "<br />\n");
        }
    }
}
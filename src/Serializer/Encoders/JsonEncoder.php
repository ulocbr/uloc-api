<?php
/**
 * Este arquivo é parte do código fonte Uloc
 *
 * (c) Tiago Felipe <tiago@tiagofelipe.com>
 *
 * Para informações completas dos direitos autorais, por favor veja o arquivo LICENSE
 * distribuído junto com o código fonte.
 */


namespace Uloc\ApiBundle\Serializer\Encoders;


class JsonEncoder implements EncoderInterface
{

    public function encode($data)
    {
        return \json_encode($data);
    }

}
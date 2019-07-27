<?php
/**
 * Este arquivo é parte do código fonte Uloc
 *
 * (c) Tiago Felipe <tiago@tiagofelipe.com>
 *
 * Para informações completas dos direitos autorais, por favor veja o arquivo LICENSE
 * distribuído junto com o código fonte.
 */

namespace Uloc\ApiBundle\Entity;

use Uloc\ApiBundle\Serializer\ApiRepresentationMetadataInterface;

abstract class CommonEntity
{

    abstract static function loadApiRepresentation(ApiRepresentationMetadataInterface $representation);

}

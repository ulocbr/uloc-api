<?php
/**
 * Este arquivo é parte do código fonte Uloc
 *
 * (c) Tiago Felipe <tiago@tiagofelipe.com>
 *
 * Para informações completas dos direitos autorais, por favor veja o arquivo LICENSE
 * distribuído junto com o código fonte.
 */

namespace Uloc\ApiBundle\Serializer;


interface ApiRepresentationMetadataInterface
{

    /**
     * @param $groupName
     * @return $this
     */
    public function setGroup($groupName);

    /**
     * @param $props
     * @return $this
     */
    public function addProperties($props);
    public function build();

}